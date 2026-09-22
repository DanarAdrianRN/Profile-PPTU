@extends('layout.app')
@section('content')
@include('components.header')
    <style>
        .tour-hero {
                background:
                    linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                    url("{{ asset('assets/masjid.jpeg') }}");
                background-repeat: no-repeat;
                background-position: center;
                background-size: cover;
                min-height: 90vh;
                position: relative;
                display: flex;
                align-items: center;

            }
    </style>
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

                        {{-- <div class="mini-map">
                            <div class="map-header">
                                <h4>Denah Pondok</h4>
                            </div>
                            <div class="map-placeholder">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span>Interactive Map</span>
                            </div>
                        </div> --}}
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
                                    <p>Klik ikon hotspot informasi untuk melihat detail nama dan penjelasan lokasi.</p>
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

            // =========================================================
            // GLOBAL VARIABLE
            // =========================================================

            let viewer = null;
            let currentMarzipanoScene = null;
            let isSceneTransitioning = false;

            let currentInitialView = {
                yaw: 0,
                pitch: 0,
                fov: Math.PI / 2
            };


            // =========================================================
            // BASIC VIEW CONTROL
            // =========================================================

            function setViewParameters(parameters) {
                const view = currentMarzipanoScene?.view?.();

                if (!view) return;

                view.setParameters(parameters);
            }


            // =========================================================
            // FULLSCREEN
            // =========================================================

            const fullscreenButton =
                document.getElementById('fullscreenTourButton');

            const fullscreenTarget =
                document.querySelector('.tour-viewer-wrapper');


            fullscreenButton?.addEventListener('click', async function() {

                if (!fullscreenTarget) return;

                try {

                    if (!document.fullscreenElement) {

                        if (fullscreenTarget.requestFullscreen) {

                            await fullscreenTarget.requestFullscreen();

                        } else if (
                            fullscreenTarget.webkitRequestFullscreen
                        ) {

                            fullscreenTarget.webkitRequestFullscreen();
                        }

                    } else {

                        if (document.exitFullscreen) {

                            await document.exitFullscreen();

                        } else if (
                            document.webkitExitFullscreen
                        ) {

                            document.webkitExitFullscreen();
                        }
                    }

                } catch (error) {

                    console.error(
                        'Fullscreen error:',
                        error
                    );
                }
            });


            document.addEventListener(
                'fullscreenchange',
                updateFullscreenState
            );


            document.addEventListener(
                'webkitfullscreenchange',
                updateFullscreenState
            );


            function updateFullscreenState() {

                const isFullscreen =
                    document.fullscreenElement ||
                    document.webkitFullscreenElement;


                const icon =
                    fullscreenButton?.querySelector('i');


                if (isFullscreen) {

                    icon?.classList.remove('fa-expand');
                    icon?.classList.add('fa-compress');

                    fullscreenButton?.setAttribute(
                        'aria-label',
                        'Keluar Fullscreen'
                    );

                } else {

                    icon?.classList.remove('fa-compress');
                    icon?.classList.add('fa-expand');

                    fullscreenButton?.setAttribute(
                        'aria-label',
                        'Fullscreen'
                    );
                }


                setTimeout(() => {

                    if (viewer) {

                        viewer.updateSize();

                        requestAnimationFrame(() => {
                            viewer.updateSize();
                        });
                    }

                }, 200);
            }


            // =========================================================
            // RESET VIEW
            // =========================================================

            document
                .getElementById('resetTourButton')
                ?.addEventListener('click', function() {

                    setViewParameters(
                        currentInitialView
                    );
                });


            // =========================================================
            // CENTER VIEW
            // =========================================================

            document
                .getElementById('centerTourButton')
                ?.addEventListener('click', function() {

                    setViewParameters({
                        yaw: currentInitialView.yaw,
                        pitch: currentInitialView.pitch
                    });
                });


            // =========================================================
            // VIEWER ELEMENT
            // =========================================================

            const viewerElement =
                document.getElementById("panoramaViewer");


            if (!viewerElement) return;


            /*
             * Fallback agar ketika texture berikutnya
             * belum siap, tidak muncul flash putih.
             */
            viewerElement.style.backgroundColor = '#000';


            @if (!$activeScene)

                return;

            @endif


            // =========================================================
            // DATA FROM LARAVEL
            // =========================================================

            const tourScenes =
                @json($tourScenes);


            const activeSceneId =
                @json($activeScene?->id);


            const sceneDataById =
                new Map(

                    tourScenes.map(
                        scene => [
                            Number(scene.id),
                            scene
                        ]
                    )
                );


            const marzipanoScenes =
                new Map();


            /*
             * Cache preload panorama.
             */
            const panoramaPreloadCache =
                new Map();


            const locationItems =
                document.querySelectorAll(
                    '.location-item[data-scene-id]'
                );


            const titleElement =
                document.querySelector(
                    '.viewer-header h3'
                );


            const defaultFov =
                Math.PI / 2;


            const initialSceneData =
                sceneDataById.get(
                    Number(activeSceneId)
                );


            if (!initialSceneData?.panoramaUrl) {

                return;
            }


            // =========================================================
            // CREATE VIEWER
            // =========================================================

            viewer =
                new Marzipano.Viewer(
                    viewerElement
                );


            // =========================================================
            // VIEW LIMIT
            // =========================================================

            const limiter =
                Marzipano
                    .RectilinearView
                    .limit
                    .traditional(
                        1024,
                        120 * Math.PI / 180
                    );


            // =========================================================
            // GEOMETRY
            // =========================================================

            const geometry =
                new Marzipano.EquirectGeometry([
                    {
                        width: 4000
                    }
                ]);


            // =========================================================
            // PRELOAD PANORAMA
            // =========================================================

            function preloadPanorama(url) {

                if (!url) {

                    return Promise.resolve();
                }


                /*
                 * Jika pernah diload,
                 * gunakan promise yang sama.
                 */

                if (
                    panoramaPreloadCache.has(url)
                ) {

                    return panoramaPreloadCache.get(url);
                }


                const promise =
                    new Promise((resolve) => {

                        const image =
                            new Image();


                        image.onload = function() {

                            resolve(true);
                        };


                        /*
                         * Jangan blokir navigasi walaupun
                         * gambar gagal dipreload.
                         */
                        image.onerror = function() {

                            console.warn(
                                'Gagal preload panorama:',
                                url
                            );

                            resolve(false);
                        };


                        image.src = url;
                    });


                panoramaPreloadCache.set(
                    url,
                    promise
                );


                return promise;
            }


            // =========================================================
            // PRELOAD ALL PANORAMAS
            // =========================================================

            function preloadAllPanoramas() {

                tourScenes.forEach(
                    scene => {

                        if (
                            scene?.panoramaUrl &&
                            Number(scene.id) !==
                            Number(activeSceneId)
                        ) {

                            preloadPanorama(
                                scene.panoramaUrl
                            );
                        }
                    }
                );
            }


            /*
             * Jangan mengganggu loading scene pertama.
             * Preload panorama lain setelah browser
             * mulai idle.
             */

            if ('requestIdleCallback' in window) {

                requestIdleCallback(
                    preloadAllPanoramas
                );

            } else {

                setTimeout(
                    preloadAllPanoramas,
                    1000
                );
            }


            // =========================================================
            // ANIMATION HELPERS
            // =========================================================

            function lerp(
                start,
                end,
                progress
            ) {

                return start +
                    (
                        end - start
                    ) *
                    progress;
            }


            function clamp(
                value,
                min,
                max
            ) {

                return Math.min(
                    Math.max(
                        value,
                        min
                    ),
                    max
                );
            }


            function easeInOutCubic(t) {

                return t < 0.5

                    ? 4 * t * t * t

                    : 1 -
                        Math.pow(
                            -2 * t + 2,
                            3
                        ) / 2;
            }


            function easeOutCubic(t) {

                return 1 -
                    Math.pow(
                        1 - t,
                        3
                    );
            }


            function shortestAngle(
                from,
                to
            ) {

                return Math.atan2(

                    Math.sin(
                        to - from
                    ),

                    Math.cos(
                        to - from
                    )
                );
            }


            // =========================================================
            // INITIAL VIEW
            // =========================================================

            function getInitialView(sceneData) {

                return {

                    yaw: Number(
                        sceneData
                            ?.initialView
                            ?.yaw ??
                        0
                    ),

                    pitch: Number(
                        sceneData
                            ?.initialView
                            ?.pitch ??
                        0
                    ),

                    fov: Number(
                        sceneData
                            ?.initialView
                            ?.fov ??
                        defaultFov
                    )
                };
            }


            // =========================================================
            // HOTSPOT ARRIVAL VIEW
            // =========================================================

            function getArrivalView(hotspot) {

                const hasCustomView =

                    hotspot.targetYaw !== null &&
                    hotspot.targetYaw !== undefined ||

                    hotspot.targetPitch !== null &&
                    hotspot.targetPitch !== undefined ||

                    hotspot.targetFov !== null &&
                    hotspot.targetFov !== undefined;


                if (!hasCustomView) {

                    return null;
                }


                return {

                    yaw:
                        hotspot.targetYaw,

                    pitch:
                        hotspot.targetPitch,

                    fov:
                        hotspot.targetFov
                };
            }


            // =========================================================
            // RESOLVE VIEW
            // =========================================================

            function resolveView(
                sceneData,
                arrivalView = null
            ) {

                const initial =
                    getInitialView(
                        sceneData
                    );


                if (!arrivalView) {

                    return initial;
                }


                return {

                    yaw: Number(

                        arrivalView.yaw ??
                        initial.yaw
                    ),

                    pitch: Number(

                        arrivalView.pitch ??
                        initial.pitch
                    ),

                    fov: Number(

                        arrivalView.fov ??
                        initial.fov
                    )
                };
            }


            // =========================================================
            // CREATE HOTSPOT ELEMENT
            // =========================================================

            function createHotspotElement(
                hotspot
            ) {

                const element =
                    document.createElement(
                        'div'
                    );


                element.classList.add(

                    hotspot.tipe ===
                    'information'

                        ? 'info-hotspot'

                        : 'hotspot-arrow'
                );


                // =====================================================
                // INFORMATION HOTSPOT
                // =====================================================

                if (
                    hotspot.tipe ===
                    'information'
                ) {

                    element.innerHTML = `

                        <div class="info-icon">

                            <i class="fa-solid fa-info"></i>

                        </div>

                        <div class="info-popup">

                            <h5>
                                ${hotspot.judul ?? 'Informasi Lokasi'}
                            </h5>

                            <p>
                                ${hotspot.deskripsi ?? ''}
                            </p>

                        </div>
                    `;


                    return element;
                }


                // =====================================================
                // NAVIGATION ICON
                // =====================================================

                const navigationIcons = {

                    arrow:
                        'fa-circle-chevron-right',

                    'arrow-right':
                        'fa-circle-chevron-right',

                    'arrow-up':
                        'fa-circle-chevron-up',

                    'arrow-down':
                        'fa-circle-chevron-down',

                    'arrow-left':
                        'fa-circle-chevron-left',

                    door:
                        'fa-door-open',

                    camera:
                        'fa-camera'
                };


                element.innerHTML = `

                    <i class="fa-solid ${
                        navigationIcons[
                            hotspot.icon
                        ] ||
                        navigationIcons.arrow
                    }"></i>

                `;


                // =====================================================
                // PRELOAD TARGET OF THIS HOTSPOT
                // =====================================================

                if (
                    hotspot.targetSceneId
                ) {

                    const targetSceneData =
                        sceneDataById.get(
                            Number(
                                hotspot.targetSceneId
                            )
                        );


                    if (
                        targetSceneData?.panoramaUrl
                    ) {

                        preloadPanorama(
                            targetSceneData.panoramaUrl
                        );
                    }
                }


                // =====================================================
                // CLICK
                // =====================================================

                element.addEventListener(
                    'click',
                    async function(event) {

                        event.preventDefault();

                        event.stopPropagation();


                        if (
                            isSceneTransitioning
                        ) {

                            return;
                        }


                        // =============================================
                        // NAVIGATION TO ANOTHER SCENE
                        // =============================================

                        if (
                            hotspot.targetSceneId
                        ) {

                            const targetData =
                                sceneDataById.get(
                                    Number(
                                        hotspot.targetSceneId
                                    )
                                );


                            /*
                             * Pastikan file panorama setidaknya
                             * sudah masuk browser cache sebelum
                             * animasi dimulai.
                             */

                            if (
                                targetData?.panoramaUrl
                            ) {

                                await preloadPanorama(
                                    targetData.panoramaUrl
                                );
                            }


                            streetViewTransition(

                                hotspot.targetSceneId,

                                hotspot,

                                true,

                                getArrivalView(
                                    hotspot
                                )
                            );


                            return;
                        }


                        // =============================================
                        // NAVIGATION TO URL
                        // =============================================

                        if (
                            hotspot.targetUrl
                        ) {

                            window.location.href =
                                hotspot.targetUrl;
                        }
                    }
                );


                return element;
            }


            // =========================================================
            // BUILD SCENE
            // =========================================================

            function buildScene(
                sceneData
            ) {

                if (
                    !sceneData?.panoramaUrl
                ) {

                    return null;
                }


                const sceneId =
                    Number(
                        sceneData.id
                    );


                /*
                 * Return existing scene.
                 */

                if (
                    marzipanoScenes.has(
                        sceneId
                    )
                ) {

                    return marzipanoScenes.get(
                        sceneId
                    );
                }


                const source =
                    Marzipano
                        .ImageUrlSource
                        .fromString(
                            sceneData.panoramaUrl
                        );


                const view =
                    new Marzipano
                        .RectilinearView(
                            null,
                            limiter
                        );


                const scene =
                    viewer.createScene({

                        source,

                        geometry,

                        view,

                        /*
                         * Pertahankan level pertama
                         * sebagai fallback.
                         */
                        pinFirstLevel: true
                    });


                // =====================================================
                // CREATE HOTSPOTS
                // =====================================================

                (
                    sceneData.hotspots ||
                    []
                ).forEach(
                    hotspot => {

                        scene
                            .hotspotContainer()
                            .createHotspot(

                                createHotspotElement(
                                    hotspot
                                ),

                                {
                                    yaw:
                                        Number(
                                            hotspot.yaw
                                        ),

                                    pitch:
                                        Number(
                                            hotspot.pitch
                                        )
                                }
                            );
                    }
                );


                marzipanoScenes.set(
                    sceneId,
                    scene
                );


                return scene;
            }


            // =========================================================
            // ACTIVE SIDEBAR
            // =========================================================

            function setActiveSidebar(
                sceneId
            ) {

                locationItems.forEach(
                    item => {

                        item
                            .classList
                            .toggle(

                                'active',

                                Number(
                                    item.dataset.sceneId
                                ) ===

                                Number(
                                    sceneId
                                )
                            );
                    }
                );
            }


            // =========================================================
            // STREET VIEW STYLE TRANSITION
            // =========================================================

            function streetViewTransition(
                sceneId,
                hotspot,
                shouldPushState = true,
                arrivalView = null
            ) {

                if (
                    isSceneTransitioning
                ) {

                    return false;
                }


                const numericSceneId =
                    Number(sceneId);


                const sceneData =
                    sceneDataById.get(
                        numericSceneId
                    );


                if (
                    !sceneData ||
                    !currentMarzipanoScene
                ) {

                    return false;
                }


                const nextScene =
                    buildScene(
                        sceneData
                    );


                if (!nextScene) {

                    return false;
                }


                isSceneTransitioning =
                    true;


                // =====================================================
                // DISABLE INPUT TEMPORARILY
                // =====================================================

                const previousPointerEvents =
                    viewerElement
                        .style
                        .pointerEvents;


                viewerElement.style
                    .pointerEvents =
                    'none';


                // =====================================================
                // CURRENT VIEW
                // =====================================================

                const oldScene =
                    currentMarzipanoScene;


                const oldView =
                    oldScene.view();


                const oldParameters =
                    oldView.parameters();


                // =====================================================
                // TARGET VIEW
                // =====================================================

                const finalView =
                    resolveView(
                        sceneData,
                        arrivalView
                    );


                // =====================================================
                // MOVEMENT DIRECTION
                // =====================================================

                const hotspotYaw =
                    Number(
                        hotspot?.yaw ??
                        oldParameters.yaw
                    );


                const hotspotPitch =
                    Number(
                        hotspot?.pitch ??
                        oldParameters.pitch
                    );


                const yawDifference =
                    shortestAngle(

                        oldParameters.yaw,

                        hotspotYaw
                    );


                const pitchDifference =

                    hotspotPitch -

                    oldParameters.pitch;


                /*
                 * Hanya geser sedikit.
                 *
                 * Tidak memutar kamera hingga
                 * menghadap hotspot.
                 */

                const outgoingYawMovement =
                    clamp(

                        yawDifference *
                        0.10,

                        -0.075,

                        0.075
                    );


                const outgoingPitchMovement =
                    clamp(

                        pitchDifference *
                        0.06,

                        -0.03,

                        0.03
                    );


                // =====================================================
                // TRANSITION CONFIG
                // =====================================================

                /*
                 * Total durasi transisi.
                 */
                const duration =
                    520;


                /*
                 * Panorama baru masuk pada 48%.
                 */
                const swapPoint =
                    0.48;


                /*
                 * Blur maksimal.
                 */
                const maxBlur =
                    2.5;


                /*
                 * Zoom maju.
                 *
                 * 1    = tidak zoom
                 * 0.8  = sedikit zoom
                 * 0.7  = lebih kuat
                 */
                const zoomFactor =
                    0.78;


                // =====================================================
                // OLD SCENE
                // =====================================================

                const startFov =
                    Number(
                        oldParameters.fov
                    );


                const zoomedOldFov =
                    startFov *
                    zoomFactor;


                // =====================================================
                // NEW SCENE
                // =====================================================

                const targetFinalFov =
                    Number(
                        finalView.fov
                    );


                const targetStartFov =
                    targetFinalFov *
                    zoomFactor;


                // =====================================================
                // CONTINUOUS OFFSET
                // =====================================================

                const arrivalYawOffset =
                    clamp(

                        outgoingYawMovement *
                        0.30,

                        -0.025,

                        0.025
                    );


                const arrivalPitchOffset =
                    clamp(

                        outgoingPitchMovement *
                        0.30,

                        -0.012,

                        0.012
                    );


                const startArrivalYaw =

                    Number(
                        finalView.yaw
                    ) -

                    arrivalYawOffset;


                const startArrivalPitch =

                    Number(
                        finalView.pitch
                    ) -

                    arrivalPitchOffset;


                // =====================================================
                // STORE ORIGINAL STYLE
                // =====================================================

                const originalFilter =
                    viewerElement
                        .style
                        .filter;


                const originalTransform =
                    viewerElement
                        .style
                        .transform;


                const originalTransformOrigin =
                    viewerElement
                        .style
                        .transformOrigin;


                const originalWillChange =
                    viewerElement
                        .style
                        .willChange;


                viewerElement.style
                    .willChange =
                    'filter, transform';


                viewerElement.style
                    .transformOrigin =
                    'center center';


                let startTime =
                    null;


                let sceneSwitched =
                    false;


                // =====================================================
                // ANIMATION LOOP
                // =====================================================

                function animate(
                    timestamp
                ) {

                    if (
                        startTime === null
                    ) {

                        startTime =
                            timestamp;
                    }


                    const elapsed =

                        timestamp -

                        startTime;


                    const progress =
                        Math.min(

                            elapsed /
                            duration,

                            1
                        );


                    // =================================================
                    // PHASE 1
                    // OLD PANORAMA
                    // =================================================

                    if (
                        progress <
                        swapPoint
                    ) {

                        const localProgress =

                            progress /

                            swapPoint;


                        const eased =
                            easeInOutCubic(
                                localProgress
                            );


                        oldView.setParameters({

                            yaw:

                                oldParameters.yaw +

                                outgoingYawMovement *
                                eased,


                            pitch:

                                oldParameters.pitch +

                                outgoingPitchMovement *
                                eased,


                            fov:

                                lerp(

                                    startFov,

                                    zoomedOldFov,

                                    eased
                                )
                        });


                        /*
                         * Blur meningkat.
                         */

                        const blur =

                            maxBlur *
                            eased;


                        /*
                         * Sedikit scale.
                         */

                        const scale =

                            1 +

                            (
                                0.01 *
                                eased
                            );


                        viewerElement.style
                            .filter =

                            `blur(${blur}px)`;


                        viewerElement.style
                            .transform =

                            `scale(${scale})`;
                    }


                    // =================================================
                    // PHASE 2
                    // TARGET PANORAMA
                    // =================================================

                    else {

                        if (
                            !sceneSwitched
                        ) {

                            /*
                             * Scene tujuan dimulai
                             * dalam keadaan sedikit zoom.
                             */

                            nextScene
                                .view()
                                .setParameters({

                                    yaw:
                                        startArrivalYaw,

                                    pitch:
                                        startArrivalPitch,

                                    fov:
                                        targetStartFov
                                });


                            /*
                             * Pindah langsung.
                             *
                             * Tidak menggunakan fade.
                             */
                            nextScene.switchTo({

                                transitionDuration:
                                    0
                            });


                            currentMarzipanoScene =
                                nextScene;


                            currentInitialView =
                                getInitialView(
                                    sceneData
                                );


                            setActiveSidebar(
                                numericSceneId
                            );


                            // =========================================
                            // TITLE
                            // =========================================

                            if (
                                titleElement
                            ) {

                                titleElement.textContent =

                                    sceneData.namaLokasi ||

                                    'Virtual Tour';
                            }


                            // =========================================
                            // HISTORY
                            // =========================================

                            if (
                                shouldPushState
                            ) {

                                history.pushState(

                                    {
                                        sceneId:
                                            numericSceneId,

                                        arrivalView:
                                            arrivalView
                                    },

                                    '',

                                    sceneData.url
                                );
                            }


                            sceneSwitched =
                                true;
                        }


                        /*
                         * Lanjutkan gerakan
                         * setelah scene berganti.
                         */

                        const localProgress =

                            (
                                progress -
                                swapPoint
                            ) /

                            (
                                1 -
                                swapPoint
                            );


                        const eased =
                            easeOutCubic(
                                localProgress
                            );


                        const newView =
                            nextScene.view();


                        newView.setParameters({

                            yaw:

                                lerp(

                                    startArrivalYaw,

                                    Number(
                                        finalView.yaw
                                    ),

                                    eased
                                ),


                            pitch:

                                lerp(

                                    startArrivalPitch,

                                    Number(
                                        finalView.pitch
                                    ),

                                    eased
                                ),


                            fov:

                                lerp(

                                    targetStartFov,

                                    targetFinalFov,

                                    eased
                                )
                        });


                        /*
                         * Blur menghilang.
                         */

                        const blur =

                            maxBlur *
                            (
                                1 -
                                eased
                            );


                        const scale =

                            1 +

                            (
                                0.01 *
                                (
                                    1 -
                                    eased
                                )
                            );


                        viewerElement.style
                            .filter =

                            `blur(${blur}px)`;


                        viewerElement.style
                            .transform =

                            `scale(${scale})`;
                    }


                    // =================================================
                    // CONTINUE
                    // =================================================

                    if (
                        progress < 1
                    ) {

                        requestAnimationFrame(
                            animate
                        );

                        return;
                    }


                    // =================================================
                    // FINISH
                    // =================================================

                    nextScene
                        .view()
                        .setParameters(
                            finalView
                        );


                    viewerElement.style
                        .filter =
                        originalFilter;


                    viewerElement.style
                        .transform =
                        originalTransform;


                    viewerElement.style
                        .transformOrigin =
                        originalTransformOrigin;


                    viewerElement.style
                        .willChange =
                        originalWillChange;


                    viewerElement.style
                        .pointerEvents =
                        previousPointerEvents;


                    isSceneTransitioning =
                        false;
                }


                requestAnimationFrame(
                    animate
                );


                return true;
            }


            // =========================================================
            // NORMAL SWITCH SCENE
            // =========================================================
            //
            // Digunakan untuk:
            //
            // - Initial scene
            // - Sidebar
            // - Browser back / forward
            //
            // =========================================================

            function switchScene(
                sceneId,
                shouldPushState = false,
                arrivalView = null,
                transitionDuration = 650
            ) {

                const numericSceneId =
                    Number(sceneId);


                const sceneData =
                    sceneDataById.get(
                        numericSceneId
                    );


                if (!sceneData) {

                    return false;
                }


                const nextScene =
                    buildScene(
                        sceneData
                    );


                if (!nextScene) {

                    return false;
                }


                const viewParameters =
                    resolveView(

                        sceneData,

                        arrivalView
                    );


                nextScene
                    .view()
                    .setParameters(
                        viewParameters
                    );


                nextScene.switchTo({

                    transitionDuration:
                        transitionDuration
                });


                currentMarzipanoScene =
                    nextScene;


                currentInitialView =
                    getInitialView(
                        sceneData
                    );


                setActiveSidebar(
                    numericSceneId
                );


                if (
                    titleElement
                ) {

                    titleElement.textContent =

                        sceneData.namaLokasi ||

                        'Virtual Tour';
                }


                if (
                    shouldPushState
                ) {

                    history.pushState(

                        {
                            sceneId:
                                numericSceneId,

                            arrivalView:
                                arrivalView
                        },

                        '',

                        sceneData.url
                    );
                }


                return true;
            }


            // =========================================================
            // SIDEBAR CLICK
            // =========================================================

            locationItems.forEach(
                item => {

                    item.addEventListener(
                        'click',
                        async function(event) {

                            event.preventDefault();


                            if (
                                isSceneTransitioning
                            ) {

                                return;
                            }


                            const sceneId =
                                Number(
                                    this.dataset.sceneId
                                );


                            const targetData =
                                sceneDataById.get(
                                    sceneId
                                );


                            /*
                             * Preload dulu supaya sidebar
                             * juga tidak flash putih.
                             */
                            if (
                                targetData?.panoramaUrl
                            ) {

                                await preloadPanorama(
                                    targetData.panoramaUrl
                                );
                            }


                            switchScene(
                                sceneId,
                                true
                            );
                        }
                    );
                }
            );


            // =========================================================
            // BROWSER BACK / FORWARD
            // =========================================================

            window.addEventListener(
                'popstate',
                async function(event) {

                    if (
                        isSceneTransitioning
                    ) {

                        return;
                    }


                    const params =
                        new URLSearchParams(
                            window.location.search
                        );


                    const sceneId =

                        Number(

                            event.state
                                ?.sceneId ||

                            params.get(
                                'scene'
                            ) ||

                            activeSceneId
                        );


                    const targetData =
                        sceneDataById.get(
                            sceneId
                        );


                    if (
                        targetData?.panoramaUrl
                    ) {

                        await preloadPanorama(
                            targetData.panoramaUrl
                        );
                    }


                    switchScene(

                        sceneId,

                        false,

                        event.state
                            ?.arrivalView ||

                        null
                    );
                }
            );


            // =========================================================
            // INITIAL HISTORY
            // =========================================================

            history.replaceState(

                {
                    sceneId:
                        Number(
                            activeSceneId
                        ),

                    arrivalView:
                        null
                },

                '',

                window.location.href
            );


            // =========================================================
            // FIRST SCENE
            // =========================================================

            switchScene(
                activeSceneId,
                false,
                null,
                0
            );

        });
    </script>
@endpush

    @include('components.footer')
@endsection
