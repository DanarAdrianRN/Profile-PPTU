@extends('layout.app')

@include('components.header')

@section('content')
    <section class="virtual-tour">
        <div class="tour-hero">
            <div class="hero-overlay"></div>
            <div class="container">
                <div class="hero-content">
                    <span class="badge-tour">
                        <i class="fa-solid fa-vr-cardboard"></i>
                        Virtual Tour 360°
                    </span>
                    <h1>Jelajahi Lingkungan Pondok Secara Interaktif</h1>
                    <p>Rasakan pengalaman berkeliling pondok pesantren secara virtual seperti berada langsung di lokasi.</p>
                </div>
            </div>
        </div>

        <div class="tour-section">
            <div class="container-fluid">
                <div class="tour-layout">
                    <aside class="tour-sidebar">
                        <div class="sidebar-header">
                            <h3>Lokasi Virtual Tour</h3>
                            <p>Pilih area untuk dijelajahi</p>
                        </div>

                        <div class="location-list">
                            @forelse ($scenes as $scene)
                                <a href="{{ route('virtual-tour', ['scene' => $scene->id]) }}"
                                    data-scene-id="{{ $scene->id }}"
                                    class="location-item {{ $activeScene?->id === $scene->id ? 'active' : '' }}">
                                    <div class="location-thumb">
                                        @if ($scene->thumbnail_icon)
                                            <i class="fa-solid {{ $scene->thumbnail_icon }}"></i>
                                        @else
                                            <img src="{{ $scene->thumbnail_url }}" alt="{{ $scene->nama_lokasi }}">
                                        @endif
                                    </div>
                                    <div>
                                        <h5>{{ $scene->nama_lokasi }}</h5>
                                        <span>{{ $scene->deskripsi ?? 'Klik untuk masuk lokasi' }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="location-item" style="cursor: default;">
                                    <div>
                                        <h5>Belum ada lokasi</h5>
                                        <span>Silakan tambahkan scene di admin.</span>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <div class="mini-map">
                            <div class="map-header">
                                <h4>Denah Pondok</h4>
                            </div>
                            <div class="map-placeholder">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span>Interactive Map</span>
                            </div>
                        </div>
                    </aside>

                    <div class="tour-viewer-wrapper">
                        <div class="viewer-header">
                            <div>
                                <h3>{{ $activeScene?->nama_lokasi ?? 'Virtual Tour' }}</h3>
                                <p>Klik dan geser untuk melihat area 360°</p>
                            </div>
                            <div class="viewer-action">
                                <button type="button" aria-label="Fullscreen" id="fullscreenTourButton">
                                    <i class="fa-solid fa-expand"></i>
                                </button>
                                <button type="button" aria-label="Reset view" id="resetTourButton">
                                    <i class="fa-solid fa-rotate"></i>
                                </button>
                                <button type="button" aria-label="Center" id="centerTourButton">
                                    <i class="fa-solid fa-location-crosshairs"></i>
                                </button>
                            </div>
                        </div>

                        <div class="tour-viewer">
                            @if ($activeScene?->panorama_url)
                                <div id="panoramaViewer"></div>
                            @else
                                <div class="empty-tour-viewer">
                                    <i class="fa-solid fa-panorama"></i>
                                    <h3>Panorama Belum Tersedia</h3>
                                    <p>Scene ini sudah publish, tetapi file panorama belum diunggah.</p>
                                </div>
                            @endif
                        </div>

                        <div class="viewer-info">
                            <div class="info-card">
                                <div class="info-iconn">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    <h5>Informasi Lokasi</h5>
                                    <p>Gunakan ikon hotspot informasi untuk melihat detail lokasi.</p>
                                </div>
                            </div>
                            <div class="info-card">
                                <div class="info-iconn">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                                <div>
                                    <h5>Mode 360°</h5>
                                    <p>Gunakan mouse atau sentuhan layar untuk mengelilingi panorama.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('script')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let viewer = null;
                let currentMarzipanoScene = null;

                function setViewParameters(parameters) {
                    const view = currentMarzipanoScene?.view?.();

                    if (!view) return;

                    view.setParameters(parameters);
                }

                document.getElementById('fullscreenTourButton')?.addEventListener('click', async function() {
                    const target = document.querySelector('.tour-viewer-wrapper');

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

                document.getElementById('resetTourButton')?.addEventListener('click', function() {
                    setViewParameters({
                        yaw: 0,
                        pitch: 0,
                        fov: Math.PI / 2
                    });
                });

                document.getElementById('centerTourButton')?.addEventListener('click', function() {
                    setViewParameters({
                        yaw: 0,
                        pitch: 0
                    });
                });

                const viewerElement = document.getElementById("panoramaViewer");
                if (!viewerElement) return;

                @if (!$activeScene)
                    // Tidak ada scene tersedia
                    return;
                @endif

                const tourScenes = @json($tourScenes);
                const activeSceneId = @json($activeScene?->id);
                const sceneDataById = new Map(tourScenes.map(scene => [Number(scene.id), scene]));
                const marzipanoScenes = new Map();
                const locationItems = document.querySelectorAll('.location-item[data-scene-id]');
                const titleElement = document.querySelector('.viewer-header h3');

                if (!sceneDataById.get(Number(activeSceneId))?.panoramaUrl) return;

                viewer = new Marzipano.Viewer(viewerElement);

                const limiter = Marzipano.RectilinearView.limit.traditional(
                    1024,
                    120 * Math.PI / 180
                );

                const geometry = new Marzipano.EquirectGeometry([{
                    width: 4000
                }]);

                function createHotspotElement(hotspot) {
                    const element = document.createElement('div');

                    element.classList.add(
                        hotspot.tipe === 'information' ? 'info-hotspot' : 'hotspot-arrow'
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

                        return element;
                    }

                    const navigationIcons = {
                        arrow: 'fa-circle-chevron-right',
                        'arrow-right': 'fa-circle-chevron-right',
                        'arrow-up': 'fa-circle-chevron-up',
                        'arrow-down': 'fa-circle-chevron-down',
                        'arrow-left': 'fa-circle-chevron-left',
                        door: 'fa-door-open',
                        camera: 'fa-camera',
                    };

                    element.innerHTML = `
                        <i class="fa-solid ${navigationIcons[hotspot.icon] || navigationIcons.arrow}"></i>
                    `;

                    element.addEventListener('click', function(event) {
                        event.preventDefault();
                        event.stopPropagation();

                        if (hotspot.targetSceneId && switchScene(hotspot.targetSceneId, true)) {
                            return;
                        }

                        if (hotspot.targetUrl) window.location.href = hotspot.targetUrl;
                    });

                    return element;
                }

                function buildScene(sceneData) {
                    if (!sceneData?.panoramaUrl) return null;

                    const sceneId = Number(sceneData.id);

                    if (marzipanoScenes.has(sceneId)) {
                        return marzipanoScenes.get(sceneId);
                    }

                    const source = Marzipano.ImageUrlSource.fromString(sceneData.panoramaUrl);
                    const view = new Marzipano.RectilinearView(null, limiter);
                    const scene = viewer.createScene({
                        source,
                        geometry,
                        view
                    });

                    sceneData.hotspots.forEach(hotspot => {
                        scene.hotspotContainer().createHotspot(
                            createHotspotElement(hotspot), {
                                yaw: hotspot.yaw,
                                pitch: hotspot.pitch
                            }
                        );
                    });

                    marzipanoScenes.set(sceneId, scene);

                    return scene;
                }

                function setActiveSidebar(sceneId) {
                    locationItems.forEach(item => {
                        item.classList.toggle(
                            'active',
                            Number(item.dataset.sceneId) === Number(sceneId)
                        );
                    });
                }

                function switchScene(sceneId, shouldPushState = false) {
                    const numericSceneId = Number(sceneId);
                    const sceneData = sceneDataById.get(numericSceneId);
                    const nextScene = buildScene(sceneData);

                    if (!sceneData || !nextScene) return false;

                    nextScene.switchTo();
                    currentMarzipanoScene = nextScene;
                    setActiveSidebar(numericSceneId);

                    if (titleElement) {
                        titleElement.textContent = sceneData.namaLokasi || 'Virtual Tour';
                    }

                    if (shouldPushState) {
                        history.pushState({
                                sceneId: numericSceneId
                            },
                            '',
                            sceneData.url
                        );
                    }

                    return true;
                }

                locationItems.forEach(item => {
                    item.addEventListener('click', function(event) {
                        if (!switchScene(this.dataset.sceneId, true)) return;

                        event.preventDefault();
                    });
                });

                window.addEventListener('popstate', function() {
                    const params = new URLSearchParams(window.location.search);
                    const sceneId = params.get('scene') || activeSceneId;

                    switchScene(sceneId, false);
                });

                switchScene(activeSceneId, false);
            });
        </script>
    @endpush

    @include('components.footer')
@endsection
