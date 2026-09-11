<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\LogsAdminActivity;
use App\Models\VirtualTourHotspot;
use App\Models\VirtualTourScene;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VirtualTourController extends Controller
{
    use LogsAdminActivity;

    public function index(Request $request)
    {
        $scenes = VirtualTourScene::withCount('hotspots')
            ->orderBy('urutan')
            ->latest()
            ->get();

        $activeScene = $scenes->firstWhere('id', (int) $request->scene)
            ?? $scenes->firstWhere('is_start_scene', true)
            ?? $scenes->first();

        if ($activeScene) {
            $activeScene->load([
                'hotspots.targetScene',
            ]);
        }

        $allScenes = VirtualTourScene::with([
                'hotspots.targetScene',
            ])
            ->orderBy('urutan')
            ->get();

        return view('pages.admin.media.virtual-tour', [
            'showModal' => false,
            'scenes' => $scenes,
            'activeScene' => $activeScene,
            'allScenes' => $allScenes,
            'totalScenes' => $scenes->count(),
            'totalHotspots' => VirtualTourHotspot::count(),
            'publishedScenes' => $scenes->where('status', 'published')->count(),
            'lastUpdate' => optional(VirtualTourScene::latest('updated_at')->first()?->updated_at)
                ->diffForHumans() ?? '-',
        ]);
    }

    public function storeScene(Request $request)
    {
        $request->session()->flash('virtual_tour_form', 'create_scene');
        $validated = $this->validateScene($request, true);
        $validated = $this->prepareSceneData($validated);
        $validated['slug'] = $this->uniqueSlug($validated['nama_lokasi']);
        $validated['is_start_scene'] = false;
        $validated['show_on_landing'] = $request->boolean('show_on_landing');
        $validated['urutan'] = $validated['urutan'] ?? 0;

        if ($request->hasFile('panorama')) {
            $validated['panorama'] = $request->file('panorama')
                ->store('virtual-tour/panoramas', 'public');
        }

        VirtualTourScene::create($validated);

        $this->catatAktivitas(
            'create',
            'Menambahkan scene virtual tour: ' . $validated['nama_lokasi']
        );

        return back()->with('success', 'Scene virtual tour berhasil ditambahkan');
    }

    public function updateScene(Request $request, VirtualTourScene $scene)
    {
        $validated = $this->validateScene($request);
        $validated = $this->prepareSceneData($validated);
        $validated['slug'] = $this->uniqueSlug(
            $validated['nama_lokasi'],
            $scene->id
        );
        $validated['is_start_scene'] = $scene->is_start_scene;
        $validated['show_on_landing'] = $request->boolean('show_on_landing');
        $validated['urutan'] = $validated['urutan'] ?? 0;

        if ($request->hasFile('panorama')) {
            $this->deleteFile($scene->panorama);
            $validated['panorama'] = $request->file('panorama')
                ->store('virtual-tour/panoramas', 'public');
        }

        $scene->update($validated);

        $this->catatAktivitas(
            'update',
            'Memperbarui scene virtual tour: ' . $scene->nama_lokasi,
            $scene
        );

        return redirect()
            ->route('admin-virtual-tour', ['scene' => $scene->id])
            ->with('success', 'Scene virtual tour berhasil diperbarui');
    }

    public function destroyScene(VirtualTourScene $scene)
    {
        $namaScene = $scene->nama_lokasi;

        DB::transaction(function () use ($scene) {
            // Hapus hotspot dari scene lain yang menjadikan scene ini sebagai tujuan.
            VirtualTourHotspot::where('target_scene_id', $scene->id)->delete();

            $this->deleteFile($scene->thumbnail);
            $this->deleteFile($scene->panorama);

            // Hotspot milik scene ini juga dihapus oleh foreign key cascade.
            $scene->delete();
        });

        $this->catatAktivitas(
            'delete',
            'Menghapus scene virtual tour: ' . $namaScene
        );

        return redirect()
            ->route('admin-virtual-tour')
            ->with('success', 'Scene virtual tour berhasil dihapus');
    }

    public function storeHotspot(Request $request, VirtualTourScene $scene)
    {
        $request->session()->flash('virtual_tour_form', 'create_hotspot');
        $validated = $this->validateHotspot($request, $scene->id);
        $validated = $this->prepareHotspotData($validated);
        $validated['virtual_tour_scene_id'] = $scene->id;
        $validated['is_active'] = $request->boolean('is_active');

        VirtualTourHotspot::create($validated);

        $this->catatAktivitas(
            'create',
            'Menambahkan hotspot pada scene: ' . $scene->nama_lokasi,
            $scene
        );

        return redirect()
            ->route('admin-virtual-tour', ['scene' => $scene->id])
            ->with('success', 'Hotspot berhasil ditambahkan');
    }

    public function updateHotspot(Request $request, VirtualTourHotspot $hotspot)
    {
        $validated = $this->validateHotspot($request, $hotspot->virtual_tour_scene_id);
        $validated = $this->prepareHotspotData($validated);
        $validated['is_active'] = $request->boolean('is_active');

        $hotspot->update($validated);

        $this->catatAktivitas(
            'update',
            'Memperbarui hotspot pada scene: ' . ($hotspot->scene?->nama_lokasi ?? '-'),
            $hotspot
        );

        return redirect()
            ->route('admin-virtual-tour', ['scene' => $hotspot->virtual_tour_scene_id])
            ->with('success', 'Hotspot berhasil diperbarui');
    }

    public function destroyHotspot(VirtualTourHotspot $hotspot)
    {
        $sceneId = $hotspot->virtual_tour_scene_id;

        $hotspot->delete();

        $this->catatAktivitas(
            'delete',
            'Menghapus hotspot pada scene ID ' . $sceneId
        );

        return redirect()
            ->route('admin-virtual-tour', ['scene' => $sceneId])
            ->with('success', 'Hotspot berhasil dihapus');
    }

    private function validateScene(Request $request, bool $panoramaRequired = false): array
    {
        return $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:published,draft,hidden',
            'urutan' => 'nullable|integer|min:0',
            'thumbnail' => 'required|in:building,mosque,road,field,home',
            'panorama' => [
                $panoramaRequired ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:20480',
                'dimensions:ratio=2/1',
            ],
            'initial_yaw' => 'nullable|numeric|between:-360,360',
            'initial_pitch' => 'nullable|numeric|between:-90,90',
            'initial_fov' => 'nullable|numeric|between:20,160',
        ], [
            'panorama.required' => 'Panorama wajib dipilih. Scene tidak dapat ditambahkan tanpa gambar panorama 360 derajat.',
            'panorama.image' => 'File panorama harus berupa gambar yang valid.',
            'panorama.mimes' => 'Format panorama harus JPG, JPEG, PNG, atau WEBP.',
            'panorama.max' => 'Ukuran panorama maksimal 20 MB.',
            'panorama.dimensions' => 'Gambar tidak sesuai kriteria panorama 360 derajat. Gunakan gambar equirectangular dengan rasio lebar dan tinggi 2:1.',
        ]);
    }

    private function validateHotspot(Request $request, int $sourceSceneId): array
    {
        return $request->validate([
            'tipe' => 'required|in:navigation,information',
            'target_scene_id' => [
                Rule::requiredIf($request->input('tipe') === 'navigation'),
                'nullable',
                'exists:virtual_tour_scenes,id',
                Rule::notIn([$sourceSceneId]),
            ],
            'icon' => 'required|in:arrow,arrow-right,arrow-up,arrow-down,arrow-left,info,door,camera',
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'yaw' => 'required|numeric|between:-360,360',
            'pitch' => 'required|numeric|between:-90,90',
            'target_yaw' => 'nullable|numeric|between:-360,360',
            'target_pitch' => 'nullable|numeric|between:-90,90',
            'target_fov' => 'nullable|numeric|between:20,160',
        ], [
            'target_scene_id.required' => 'Tujuan hotspot wajib dipilih untuk hotspot navigasi.',
            'target_scene_id.exists' => 'Scene tujuan hotspot tidak ditemukan.',
            'target_scene_id.not_in' => 'Tujuan hotspot harus berbeda dari scene saat ini.',
        ]);
    }

    private function prepareSceneData(array $validated): array
    {
        $validated['initial_yaw'] = $this->degreesToRadians($validated['initial_yaw'] ?? 0);
        $validated['initial_pitch'] = $this->degreesToRadians($validated['initial_pitch'] ?? 0);
        $validated['initial_fov'] = $this->normalizeFov($validated['initial_fov'] ?? null, pi() / 2);

        return $validated;
    }

    private function prepareHotspotData(array $validated): array
    {
        $validated['icon'] = $this->normalizeHotspotIcon($validated['icon']);
        $validated['target_scene_id'] = $validated['target_scene_id'] ?? null;
        $validated['yaw'] = $this->degreesToRadians($validated['yaw']);
        $validated['pitch'] = $this->degreesToRadians($validated['pitch']);
        $validated['target_yaw'] = $this->hasAngleValue($validated['target_yaw'] ?? null)
            ? $this->degreesToRadians($validated['target_yaw'])
            : null;
        $validated['target_pitch'] = $this->hasAngleValue($validated['target_pitch'] ?? null)
            ? $this->degreesToRadians($validated['target_pitch'])
            : null;
        $validated['target_fov'] = $this->normalizeFov($validated['target_fov'] ?? null);

        return $validated;
    }

    private function normalizeHotspotIcon(string $icon): string
    {
        return match ($icon) {
            'arrow' => 'arrow-right',
            default => $icon,
        };
    }

    private function degreesToRadians(float|string $value): float
    {
        return round(deg2rad((float) $value), 4);
    }

    private function hasAngleValue(null|float|string $value): bool
    {
        return $value !== null && $value !== '';
    }

    private function normalizeFov(null|float|string $value, ?float $default = null): ?float
    {
        if ($value === null || $value === '') {
            return $default === null ? null : round($default, 4);
        }

        $value = deg2rad((float) $value);

        return round(max(0.3491, min($value, 2.7925)), 4);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (
            VirtualTourScene::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
