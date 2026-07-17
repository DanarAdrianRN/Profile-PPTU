@extends('layout.app')

@section('content')
    @php
        $navigationIcons = [
            'arrow' => 'fa-circle-chevron-right',
            'arrow-right' => 'fa-circle-chevron-right',
            'arrow-up' => 'fa-circle-chevron-up',
            'arrow-down' => 'fa-circle-chevron-down',
            'arrow-left' => 'fa-circle-chevron-left',
            'door' => 'fa-door-open',
            'camera' => 'fa-camera',
        ];
    @endphp

    <div class="virtual-tour-admin">
        <div class="tour-back-button">
            <a href="{{ route('admin-dashboard') }}">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <aside class="tour-admin-sidebar">
            <div class="sidebar-top">
                <div class="sidebar-title">
                    <span>Virtual Tour CMS</span>
                    <h3>Scene Manager</h3>
                </div>

                <button class="add-scene-btn" data-toggle="modal" data-target="#modalTambahScene">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Lokasi
                </button>
            </div>

            <div class="scene-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="sceneSearch" placeholder="Cari lokasi...">
            </div>

            <div class="scene-list" id="sceneList">
                @forelse ($scenes as $scene)
                    <a href="{{ route('admin-virtual-tour', ['scene' => $scene->id]) }}"
                        class="scene-item {{ $activeScene?->id === $scene->id ? 'active' : '' }}">
                        <div class="scene-thumb">
                            @if ($scene->thumbnail_icon)
                                <i class="fa-solid {{ $scene->thumbnail_icon }}"></i>
                            @else
                                <img src="{{ $scene->thumbnail_url }}" alt="{{ $scene->nama_lokasi }}">
                            @endif
                        </div>

                        <div class="scene-info">
                            <div class="scene-top">
                                <h4>{{ $scene->nama_lokasi }}</h4>
                                <span class="scene-status {{ $scene->status }}">
                                    {{ ucfirst($scene->status) }}
                                </span>
                            </div>

                            <span>{{ $scene->hotspots_count }} Hotspot</span>
                        </div>

                        <span class="scene-option">
                            <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    </a>
                @empty
                    <div class="scene-item">
                        <div class="scene-info">
                            <div class="scene-top">
                                <h4>Belum ada lokasi</h4>
                            </div>
                            <span>Tambahkan scene pertama</span>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="tour-map-card">
                <div class="map-head">
                    <h4>Mini Map</h4>
                    <span>Denah Lokasi</span>
                </div>

                <div class="map-placeholder">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <p>Interactive Map</p>
                </div>
            </div>
        </aside>

        <div class="tour-admin-main">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="tour-admin-topbar">
                <div class="topbar-left">
                    <div class="autosave-status">
                        <i class="fa-solid fa-circle-check"></i>
                        Data tersimpan di database
                    </div>

                    <h2>{{ $activeScene?->nama_lokasi ?? 'Virtual Tour' }}</h2>
                    <p>{{ $activeScene ? 'Kelola panorama dan hotspot lokasi ini' : 'Tambahkan lokasi pertama untuk mulai' }}
                    </p>
                </div>

                <div class="topbar-action">
                    @if ($activeScene)
                        <button class="tour-btn secondary" data-toggle="modal" data-target="#modalEditScene">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Edit Scene
                        </button>

                        <form method="POST" action="{{ route('virtual-tour.scene.update', $activeScene->id) }}">
                            @csrf
                            <input type="hidden" name="nama_lokasi" value="{{ $activeScene->nama_lokasi }}">
                            <input type="hidden" name="deskripsi" value="{{ $activeScene->deskripsi }}">
                            <input type="hidden" name="urutan" value="{{ $activeScene->urutan }}">
                            <input type="hidden" name="thumbnail"
                                value="{{ $activeScene->thumbnail_icon ? $activeScene->thumbnail : 'building' }}">
                            <input type="hidden" name="status" value="published">
                            @if ($activeScene->show_on_landing)
                                <input type="hidden" name="show_on_landing" value="1">
                            @endif
                            <button type="submit" class="tour-btn primary">
                                <i class="fa-solid fa-upload"></i>
                                Publish
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="tour-stats">
                <div class="tour-stat-card">
                    <div class="icon blue">
                        <i class="fa-solid fa-panorama"></i>
                    </div>
                    <div>
                        <span>Total Scene</span>
                        <h3>{{ $totalScenes }}</h3>
                    </div>
                </div>

                <div class="tour-stat-card">
                    <div class="icon green">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <span>Total Hotspot</span>
                        <h3>{{ $totalHotspots }}</h3>
                    </div>
                </div>

                <div class="tour-stat-card">
                    <div class="icon orange">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <div>
                        <span>Published</span>
                        <h3>{{ $publishedScenes }}</h3>
                    </div>
                </div>

                <div class="tour-stat-card">
                    <div class="icon purple">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <span>Last Update</span>
                        <h3>{{ $lastUpdate }}</h3>
                    </div>
                </div>
            </div>

            <div class="tour-editor-layout">
                <div class="tour-viewer-card">
                    <div class="viewer-head">
                        <div>
                            <h3>Panorama Viewer</h3>
                            <p>Preview panorama 360° dari scene aktif</p>
                        </div>

                        <div class="viewer-tools">
                            <button class="active" type="button">
                                <i class="fa-solid fa-arrow-pointer"></i>
                            </button>
                            <button type="button" data-toggle="modal" data-target="#modalTambahHotspot"
                                {{ $activeScene ? '' : 'disabled' }}>
                                <i class="fa-solid fa-location-dot"></i>
                            </button>
                            <button type="button" data-fullscreen-target=".tour-viewer-card">
                                <i class="fa-solid fa-expand"></i>
                            </button>
                        </div>
                    </div>

                    <div class="tour-panorama-viewer">
                        @if ($activeScene?->panorama_url)
                            <div id="adminPanoramaViewer"></div>
                        @else
                            <div class="empty-panorama">
                                <i class="fa-solid fa-panorama"></i>
                                <h3>Belum Ada Panorama</h3>
                                <p>Upload panorama 360° pada scene settings</p>
                            </div>
                        @endif
                    </div>

                    <div class="viewer-position">
                        <span>Scene : {{ $activeScene?->nama_lokasi ?? '-' }}</span>
                        <span>Hotspot : {{ $activeScene?->hotspots->count() ?? 0 }}</span>
                    </div>
                </div>

                <div class="tour-inspector">
                    <div class="inspector-card">
                        <div class="inspector-head">
                            <h3>Scene Settings</h3>
                        </div>

                        @if ($activeScene)
                            <div class="scene-preview">
                                @if ($activeScene->thumbnail_icon)
                                    <div class="scene-preview-icon">
                                        <i class="fa-solid {{ $activeScene->thumbnail_icon }}"></i>
                                    </div>
                                @else
                                    <img src="{{ $activeScene->thumbnail_url }}" alt="{{ $activeScene->nama_lokasi }}">
                                @endif
                            </div>

                            <form method="POST" action="{{ route('virtual-tour.scene.update', $activeScene->id) }}"
                                enctype="multipart/form-data">
                                @csrf

                                @include('pages.admin.partials.form-virtual-tour-scene', [
                                    'scene' => $activeScene,
                                ])

                                <button type="submit" class="tour-btn primary w-100">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    Simpan Scene
                                </button>
                            </form>

                            <form method="POST" action="{{ route('virtual-tour.scene.destroy', $activeScene->id) }}"
                                class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger-btn"
                                    onclick="return confirm('Hapus scene ini beserta semua hotspotnya?')">
                                    <i class="fa-solid fa-trash"></i>
                                    Hapus Scene
                                </button>
                            </form>
                        @else
                            <button class="tour-btn primary w-100" data-toggle="modal" data-target="#modalTambahScene">
                                <i class="fa-solid fa-plus"></i>
                                Tambah Scene Pertama
                            </button>
                        @endif
                    </div>

                    @if ($activeScene)
                        <div class="inspector-card">
                            <div class="inspector-head">
                                <h3>Hotspot Tools</h3>
                            </div>

                            <button type="button" class="tour-btn primary w-100" data-toggle="modal"
                                data-target="#modalTambahHotspot">
                                <i class="fa-solid fa-location-dot"></i>
                                Tambah Hotspot
                            </button>
                        </div>

                        <div class="inspector-card">
                            <div class="inspector-head">
                                <h3>Hotspot List</h3>
                            </div>

                            <div class="hotspot-list">
                                @forelse ($activeScene->hotspots as $hotspot)
                                    @php
                                        $hotspotIcon =
                                            $hotspot->tipe === 'information'
                                                ? 'fa-circle-info'
                                                : $navigationIcons[$hotspot->icon] ?? $navigationIcons['arrow'];
                                    @endphp

                                    <div class="hotspot-item">
                                        <div class="hotspot-icon {{ $hotspot->tipe === 'information' ? 'info' : '' }}">
                                            <i class="fa-solid {{ $hotspotIcon }}"></i>
                                        </div>

                                        <div>
                                            <h5>{{ ucfirst($hotspot->tipe) }}</h5>
                                            <span>{{ $hotspot->judul ?? ($hotspot->targetScene?->nama_lokasi ?? '-') }}</span>
                                        </div>

                                        <button type="button" class="scene-option" data-toggle="modal"
                                            data-target="#modalEditHotspot{{ $hotspot->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                    </div>
                                @empty
                                    <div class="hotspot-item">
                                        <div>
                                            <h5>Belum ada hotspot</h5>
                                            <span>Tambahkan titik navigasi atau informasi</span>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @php
        $hotspots =
            $activeScene?->hotspots
                ?->map(function ($hotspot) {
                    return [
                        'tipe' => $hotspot->tipe,
                        'icon' => $hotspot->icon,
                        'judul' => $hotspot->judul,
                        'deskripsi' => $hotspot->deskripsi,
                        'yaw' => (float) $hotspot->yaw,
                        'pitch' => (float) $hotspot->pitch,
                        'targetUrl' => $hotspot->target_scene_id
                            ? route('admin-virtual-tour', ['scene' => $hotspot->target_scene_id])
                            : null,
                    ];
                })
                ->values()
                ->toArray() ?? [];
    @endphp

    @push('script')
        <script>
            const sceneSearch = document.getElementById('sceneSearch');
            const sceneItems = document.querySelectorAll('#sceneList .scene-item');
            const navigationIcons = @json($navigationIcons);

            sceneSearch?.addEventListener('keyup', function() {
                const keyword = this.value.toLowerCase();

                sceneItems.forEach(item => {
                    item.style.display = item.innerText
                        .toLowerCase()
                        .includes(keyword) ? '' : 'none';
                });
            });

            const panoramaUrl = @json($activeScene?->panorama_url);
            const hotspots = @json($hotspots);
            let viewer = null;

            document.querySelectorAll('[data-fullscreen-target]').forEach(button => {
                button.addEventListener('click', async function() {
                    const target = document.querySelector(this.dataset.fullscreenTarget);

                    if (!target) return;

                    if (!document.fullscreenElement) {
                        await target.requestFullscreen?.();
                    } else {
                        await document.exitFullscreen?.();
                    }

                    if (viewer) {
                        setTimeout(() => viewer.updateSize(), 150);
                    }
                });
            });

            if (panoramaUrl && document.getElementById('adminPanoramaViewer')) {

                viewer = new Marzipano.Viewer(
                    document.getElementById('adminPanoramaViewer')
                );

                const limiter = Marzipano.RectilinearView.limit.traditional(
                    1024,
                    120 * Math.PI / 180
                );

                const source = Marzipano.ImageUrlSource.fromString(
                    panoramaUrl
                );

                const geometry = new Marzipano.EquirectGeometry([{
                    width: 4000
                }]);

                const view = new Marzipano.RectilinearView(
                    null,
                    limiter
                );

                const scene = viewer.createScene({
                    source,
                    geometry,
                    view
                });

                hotspots.forEach(hotspot => {

                    const element = document.createElement('div');

                    element.classList.add(
                        hotspot.tipe === 'information' ?
                        'info-hotspot' :
                        'hotspot-arrow'
                    );

                    if (hotspot.tipe === 'information') {

                        element.innerHTML = `
                        <div class="info-icon">
                            <i class="fa-solid fa-info"></i>
                        </div>

                        <div class="info-popup">
                            <h5>${hotspot.judul ?? 'Informasi Lokasi'}</h5>
                            <p>${hotspot.deskripsi ?? ''}</p>
                        </div>
                    `;

                    } else {

                        element.innerHTML = `
                        <i class="fa-solid ${navigationIcons[hotspot.icon] || navigationIcons.arrow}"></i>
                    `;

                        element.addEventListener('click', function() {
                            if (hotspot.targetUrl) {
                                window.location.href = hotspot.targetUrl;
                            }
                        });
                    }

                    scene.hotspotContainer().createHotspot(
                        element, {
                            yaw: hotspot.yaw,
                            pitch: hotspot.pitch
                        }
                    );
                });

                scene.switchTo();
            }
        </script>
    @endpush
@endsection
