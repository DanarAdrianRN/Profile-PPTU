<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\VirtualTourScene;
use Illuminate\Http\Request;

class VirtualTourController extends Controller
{
    public function index(Request $request)
    {
        $allScenes = VirtualTourScene::withCount('hotspots')
            ->with([
                'hotspots' => function ($q) {
                    $q->where('is_active', true)
                        ->with([
                            'targetScene' => fn ($targetQuery) => $targetQuery
                                ->where('status', 'published'),
                        ]);
                },
            ])
            ->where('status', 'published')
            ->orderBy('urutan')
            ->get();

        $scenes = $allScenes->where('show_on_landing', true)->values();

        $activeScene = $request->integer('scene')
            ? $allScenes->firstWhere('id', $request->integer('scene'))
            : null;

        $activeScene = $activeScene
            ?? $scenes->firstWhere('is_start_scene', true)
            ?? $scenes->first()
            ?? $allScenes->first();


        $hotspots = $activeScene?->hotspots
            ?->map(function ($hotspot) {
                return [
                    'tipe' => $hotspot->tipe,
                    'judul' => $hotspot->judul,
                    'deskripsi' => $hotspot->deskripsi,
                    'yaw' => (float) $hotspot->yaw,
                    'pitch' => (float) $hotspot->pitch,
                    'targetYaw' => $hotspot->target_yaw === null ? null : (float) $hotspot->target_yaw,
                    'targetPitch' => $hotspot->target_pitch === null ? null : (float) $hotspot->target_pitch,
                    'targetFov' => $hotspot->target_fov === null ? null : (float) $hotspot->target_fov,
                    'icon' => $hotspot->icon,
                    'targetSceneId' => $hotspot->target_scene_id,
                    'targetUrl' => $hotspot->targetScene
                        ? route('virtual-tour.scene.redirect', $hotspot->targetScene->id)
                        : null,
                ];
            })
            ->values()
            ->toArray() ?? [];

        $tourScenes = $allScenes
            ->map(function ($scene) {
                return [
                    'id' => $scene->id,
                    'namaLokasi' => $scene->nama_lokasi,
                    'deskripsi' => $scene->deskripsi,
                    'panoramaUrl' => $scene->panorama_url,
                    'url' => route('virtual-tour', ['scene' => $scene->id]),
                    'initialView' => [
                        'yaw' => (float) ($scene->initial_yaw ?? 0),
                        'pitch' => (float) ($scene->initial_pitch ?? 0),
                        'fov' => (float) ($scene->initial_fov ?? 1.5708),
                    ],
                    'hotspots' => $scene->hotspots
                        ->map(function ($hotspot) {
                            return [
                                'tipe' => $hotspot->tipe,
                                'judul' => $hotspot->judul,
                                'deskripsi' => $hotspot->deskripsi,
                                'yaw' => (float) $hotspot->yaw,
                                'pitch' => (float) $hotspot->pitch,
                                'targetYaw' => $hotspot->target_yaw === null ? null : (float) $hotspot->target_yaw,
                                'targetPitch' => $hotspot->target_pitch === null ? null : (float) $hotspot->target_pitch,
                                'targetFov' => $hotspot->target_fov === null ? null : (float) $hotspot->target_fov,
                                'icon' => $hotspot->icon,
                                'targetSceneId' => $hotspot->target_scene_id,
                                'targetUrl' => $hotspot->targetScene
                                    ? route('virtual-tour.scene.redirect', $hotspot->targetScene->id)
                                    : null,
                            ];
                        })
                        ->values()
                        ->toArray(),
                ];
            })
            ->values()
            ->toArray();

        return view('pages.landing-page.virtual-tour', [
            'showModal' => false,
            'scenes' => $scenes,
            'activeScene' => $activeScene,
            'hotspots' => $hotspots,
            'tourScenes' => $tourScenes,
        ]);
    }

    public function redirectToScene(VirtualTourScene $scene)
    {
        // Endpoint internal untuk navigasi hotspot.
        // Mengarahkan user ke /virtual-tour#scene-{id} (atau bisa diintegrasi query).
        return redirect()->route('virtual-tour', ['scene' => $scene->id]);
    }
}

