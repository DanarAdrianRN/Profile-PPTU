<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VirtualTourHotspot;
use App\Models\VirtualTourScene;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VirtualTourController extends Controller
{
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

        return view('pages.admin.media.virtual-tour', [
            'showModal' => false,
            'scenes' => $scenes,
            'activeScene' => $activeScene,
            'allScenes' => VirtualTourScene::orderBy('urutan')->get(),
            'totalScenes' => $scenes->count(),
            'totalHotspots' => VirtualTourHotspot::count(),
            'publishedScenes' => $scenes->where('status', 'published')->count(),
            'lastUpdate' => optional(VirtualTourScene::latest('updated_at')->first()?->updated_at)
                ->diffForHumans() ?? '-',
        ]);
    }

    public function storeScene(Request $request)
    {
        $validated = $this->validateScene($request);
        $validated['slug'] = $this->uniqueSlug($validated['nama_lokasi']);
        $validated['is_start_scene'] = false;
        $validated['show_on_landing'] = $request->boolean('show_on_landing');
        $validated['urutan'] = $validated['urutan'] ?? 0;

        if ($request->hasFile('panorama')) {
            $validated['panorama'] = $request->file('panorama')
                ->store('virtual-tour/panoramas', 'public');
        }

        VirtualTourScene::create($validated);

        return back()->with('success', 'Scene virtual tour berhasil ditambahkan');
    }

    public function updateScene(Request $request, VirtualTourScene $scene)
    {
        $validated = $this->validateScene($request);
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

        return redirect()
            ->route('admin-virtual-tour', ['scene' => $scene->id])
            ->with('success', 'Scene virtual tour berhasil diperbarui');
    }

    public function destroyScene(VirtualTourScene $scene)
    {
        $this->deleteFile($scene->thumbnail);
        $this->deleteFile($scene->panorama);
        $scene->delete();

        return redirect()
            ->route('admin-virtual-tour')
            ->with('success', 'Scene virtual tour berhasil dihapus');
    }

    public function storeHotspot(Request $request, VirtualTourScene $scene)
    {
        $validated = $this->validateHotspot($request);
        $validated = $this->prepareHotspotData($validated);
        $validated['virtual_tour_scene_id'] = $scene->id;
        $validated['is_active'] = $request->boolean('is_active');

        VirtualTourHotspot::create($validated);

        return redirect()
            ->route('admin-virtual-tour', ['scene' => $scene->id])
            ->with('success', 'Hotspot berhasil ditambahkan');
    }

    public function updateHotspot(Request $request, VirtualTourHotspot $hotspot)
    {
        $validated = $this->validateHotspot($request);
        $validated = $this->prepareHotspotData($validated);
        $validated['is_active'] = $request->boolean('is_active');

        $hotspot->update($validated);

        return redirect()
            ->route('admin-virtual-tour', ['scene' => $hotspot->virtual_tour_scene_id])
            ->with('success', 'Hotspot berhasil diperbarui');
    }

    public function destroyHotspot(VirtualTourHotspot $hotspot)
    {
        $sceneId = $hotspot->virtual_tour_scene_id;

        $hotspot->delete();

        return redirect()
            ->route('admin-virtual-tour', ['scene' => $sceneId])
            ->with('success', 'Hotspot berhasil dihapus');
    }

    private function validateScene(Request $request): array
    {
        return $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:published,draft,hidden',
            'urutan' => 'nullable|integer|min:0',
            'thumbnail' => 'required|in:building,mosque,road,field,home',
            'panorama' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:20480',
        ]);
    }

    private function validateHotspot(Request $request): array
    {
        return $request->validate([
            'tipe' => 'required|in:navigation,information',
            'target_scene_id' => 'nullable|exists:virtual_tour_scenes,id',
            'icon' => 'required|in:arrow,arrow-right,arrow-up,arrow-down,arrow-left,info,door,camera',
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'yaw' => 'required|numeric|between:-360,360',
            'pitch' => 'required|numeric|between:-90,90',
        ]);
    }

    private function prepareHotspotData(array $validated): array
    {
        $validated['icon'] = $this->normalizeHotspotIcon($validated['icon']);
        $validated['target_scene_id'] = $validated['target_scene_id'] ?? null;
        $validated['yaw'] = $this->normalizeAngle((float) $validated['yaw'], 6.2832);
        $validated['pitch'] = $this->normalizeAngle((float) $validated['pitch'], 1.5708);

        return $validated;
    }

    private function normalizeHotspotIcon(string $icon): string
    {
        return match ($icon) {
            'arrow' => 'arrow-right',
            default => $icon,
        };
    }

    private function normalizeAngle(float $value, float $radianLimit): float
    {
        if (abs($value) > $radianLimit) {
            return round(deg2rad($value), 4);
        }

        return round($value, 4);
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
