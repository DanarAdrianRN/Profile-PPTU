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
                <input type="text" id="sceneSearch" placeholder="Cari Scane...">
            </div>

            <div class="scene-list" id="sceneList">
                @forelse ($scenes as $scene)
                    <a href="{{ route('admin-virtual-tour', ['scene' => $scene->id]) }}" data-scene-id="{{ $scene->id }}"
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
        </aside>

        <div class="tour-admin-main">
            <div class="tour-admin-topbar">
                <div class="topbar-left">
                    <h2>{{ $activeScene?->nama_lokasi ?? 'Virtual Tour' }}</h2>
                    <p>{{ $activeScene ? 'Kelola panorama dan hotspot lokasi ini' : 'Tambahkan lokasi pertama untuk mulai' }}
                    </p>
                </div>

                <div class="topbar-action">
                    @if ($activeScene)
                        <form method="POST" action="{{ route('virtual-tour.scene.update', $activeScene->id) }}"
                            id="publishSceneForm">
                            @csrf
                            <input type="hidden" name="nama_lokasi" value="{{ $activeScene->nama_lokasi }}">
                            <input type="hidden" name="deskripsi" value="{{ $activeScene->deskripsi }}">
                            <input type="hidden" name="urutan" value="{{ $activeScene->urutan }}">
                            <input type="hidden" name="initial_yaw" value="{{ $activeScene->initial_yaw_degree ?? 0 }}">
                            <input type="hidden" name="initial_pitch"
                                value="{{ $activeScene->initial_pitch_degree ?? 0 }}">
                            <input type="hidden" name="initial_fov" value="{{ $activeScene->initial_fov_degree ?? 90 }}">
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
                        <div id="adminPanoramaViewer"></div>
                        <div class="empty-panorama" id="adminEmptyPanorama"
                            style="{{ $activeScene?->panorama_url ? 'display: none;' : '' }}">
                            <i class="fa-solid fa-panorama"></i>
                            <h3>Belum Ada Panorama</h3>
                            <p>Upload panorama 360° pada scene settings</p>
                        </div>
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
                            <div class="scene-preview" id="sceneSettingsPreview">
                                @if ($activeScene->thumbnail_icon)
                                    <div class="scene-preview-icon">
                                        <i class="fa-solid {{ $activeScene->thumbnail_icon }}"></i>
                                    </div>
                                @else
                                    <img src="{{ $activeScene->thumbnail_url }}" alt="{{ $activeScene->nama_lokasi }}">
                                @endif
                            </div>

                            <form method="POST" action="{{ route('virtual-tour.scene.update', $activeScene->id) }}"
                                enctype="multipart/form-data" id="sceneSettingsForm">
                                @csrf

                                @include('pages.admin.partials.form-virtual-tour-scene', [
                                    'scene' => $activeScene,
                                ])

                                <button type="submit" class="tour-btn primary w-100">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    Simpan Scene
                                </button>
                            </form>

                            <button type="button" class="danger-btn mt-3" data-toggle="modal"
                                data-target="#modalHapusScene">
                                <i class="fa-solid fa-trash"></i>
                                Hapus Scene
                            </button>
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

                            <div class="hotspot-list" id="adminHotspotList">
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

                                        <div class="hotspot-actions">
                                            <button type="button" class="hotspot-btn" data-toggle="modal"
                                                data-target="#modalEditHotspot{{ $hotspot->id }}">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button type="button" class="hotspot-btn delete" data-toggle="modal"
                                                data-target="#modalHapusHotspot"
                                                data-delete-hotspot-url="{{ route('virtual-tour.hotspot.destroy', $hotspot->id) }}"
                                                data-hotspot-label="{{ $hotspot->judul ?? ($hotspot->targetScene?->nama_lokasi ?? ucfirst($hotspot->tipe)) }}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
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
        $adminTourScenes = $allScenes
            ->map(function ($scene) {
                return [
                    'id' => $scene->id,
                    'namaLokasi' => $scene->nama_lokasi,
                    'deskripsi' => $scene->deskripsi,
                    'status' => $scene->status,
                    'urutan' => $scene->urutan,
                    'thumbnail' => $scene->thumbnail_icon ? $scene->thumbnail : 'building',
                    'thumbnailIcon' => $scene->thumbnail_icon,
                    'thumbnailUrl' => $scene->thumbnail_url,
                    'panoramaName' => $scene->panorama ? basename($scene->panorama) : null,
                    'panoramaUrl' => $scene->panorama_url,
                    'url' => route('admin-virtual-tour', ['scene' => $scene->id]),
                    'updateUrl' => route('virtual-tour.scene.update', $scene->id),
                    'destroyUrl' => route('virtual-tour.scene.destroy', $scene->id),
                    'storeHotspotUrl' => route('virtual-tour.hotspot.store', $scene->id),
                    'showOnLanding' => (bool) $scene->show_on_landing,
                    'initialView' => [
                        'yaw' => (float) ($scene->initial_yaw ?? 0),
                        'pitch' => (float) ($scene->initial_pitch ?? 0),
                        'fov' => (float) ($scene->initial_fov ?? 1.5708),
                    ],
                    'initialViewDegrees' => [
                        'yaw' => $scene->initial_yaw_degree,
                        'pitch' => $scene->initial_pitch_degree,
                        'fov' => $scene->initial_fov_degree,
                    ],
                    'hotspots' => $scene->hotspots
                        ->map(function ($hotspot) {
                            return [
                                'id' => $hotspot->id,
                                'tipe' => $hotspot->tipe,
                                'icon' => $hotspot->icon,
                                'judul' => $hotspot->judul,
                                'deskripsi' => $hotspot->deskripsi,
                                'label' => $hotspot->judul ?? ($hotspot->targetScene?->nama_lokasi ?? '-'),
                                'yaw' => (float) $hotspot->yaw,
                                'pitch' => (float) $hotspot->pitch,
                                'targetYaw' => $hotspot->target_yaw === null ? null : (float) $hotspot->target_yaw,
                                'targetPitch' =>
                                    $hotspot->target_pitch === null ? null : (float) $hotspot->target_pitch,
                                'targetFov' => $hotspot->target_fov === null ? null : (float) $hotspot->target_fov,
                                'targetSceneId' => $hotspot->target_scene_id,
                                'targetUrl' => $hotspot->target_scene_id
                                    ? route('admin-virtual-tour', ['scene' => $hotspot->target_scene_id])
                                    : null,
                                'updateUrl' => route('virtual-tour.hotspot.update', $hotspot->id),
                                'destroyUrl' => route('virtual-tour.hotspot.destroy', $hotspot->id),
                                'isActive' => (bool) $hotspot->is_active,
                            ];
                        })
                        ->values()
                        ->toArray(),
                ];
            })
            ->values()
            ->toArray();

    @endphp

    @push('script')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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

                const adminTourScenes = @json($adminTourScenes);
                const activeSceneId = @json($activeScene?->id);
                const sceneDataById = new Map(adminTourScenes.map(scene => [Number(scene.id), scene]));
                const marzipanoScenes = new Map();
                const initialView = {
                    yaw: Number(@json((float) ($activeScene->initial_yaw ?? 0))),
                    pitch: Number(@json((float) ($activeScene->initial_pitch ?? 0))),
                    fov: Number(@json((float) ($activeScene->initial_fov ?? 1.5708)))
                };
                let viewer = null;
                let currentMarzipanoScene = null;
                const defaultFov = Math.PI / 2;
                const topbarTitle = document.querySelector('.topbar-left h2');
                const topbarSubtitle = document.querySelector('.topbar-left p');
                const viewerTitle = document.querySelector('.viewer-head h3');
                const viewerPositionScene = document.querySelector('.viewer-position span:first-child');
                const viewerPositionHotspots = document.querySelector('.viewer-position span:last-child');
                const emptyPanorama = document.getElementById('adminEmptyPanorama');
                const sceneSettingsPreview = document.getElementById('sceneSettingsPreview');
                const sceneSettingsForm = document.getElementById('sceneSettingsForm');
                const editSceneForm = document.getElementById('formEditSceneVirtualTour');
                const deleteSceneForm = document.getElementById('deleteSceneForm');
                const deleteSceneName = document.getElementById('deleteSceneName');
                const deleteHotspotForm = document.getElementById('deleteHotspotForm');
                const deleteHotspotName = document.getElementById('deleteHotspotName');
                const publishSceneForm = document.getElementById('publishSceneForm');
                const addHotspotForm = document.getElementById('formTambahHotspotVirtualTour');
                const hotspotModalContainer = document.getElementById('adminHotspotModals');
                const addHotspotTitle = document.querySelector('#modalTambahHotspot .modal-title-wrap span');
                const hotspotList = document.getElementById('adminHotspotList');

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

                if (document.getElementById('adminPanoramaViewer')) {

                    viewer = new Marzipano.Viewer(
                        document.getElementById('adminPanoramaViewer')
                    );

                    const limiter = Marzipano.RectilinearView.limit.traditional(
                        1024,
                        120 * Math.PI / 180
                    );

                    const geometry = new Marzipano.EquirectGeometry([{
                        width: 4000
                    }]);

                    function getInitialView(sceneData) {
                        return {
                            yaw: Number(sceneData?.initialView?.yaw ?? 0),
                            pitch: Number(sceneData?.initialView?.pitch ?? 0),
                            fov: Number(sceneData?.initialView?.fov ?? defaultFov)
                        };
                    }

                    function getArrivalView(hotspot) {
                        const hasCustomView = hotspot.targetYaw !== null ||
                            hotspot.targetPitch !== null ||
                            hotspot.targetFov !== null;

                        if (!hasCustomView) return null;

                        return {
                            yaw: hotspot.targetYaw,
                            pitch: hotspot.targetPitch,
                            fov: hotspot.targetFov
                        };
                    }

                    function resolveView(sceneData, arrivalView = null) {
                        if (!arrivalView) {
                            return getInitialView(sceneData);
                        }

                        const initialSceneView = getInitialView(sceneData);

                        return {
                            yaw: Number(arrivalView.yaw ?? initialSceneView.yaw),
                            pitch: Number(arrivalView.pitch ?? initialSceneView.pitch),
                            fov: Number(arrivalView.fov ?? initialSceneView.fov)
                        };
                    }

                    function createHotspotElement(hotspot) {
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
                                if (hotspot.targetSceneId && switchAdminScene(
                                        hotspot.targetSceneId,
                                        true,
                                        getArrivalView(hotspot)
                                    )) {
                                    return;
                                }

                                if (hotspot.targetUrl) window.location.href = hotspot.targetUrl;
                            });
                        }

                        return element;
                    }

                    function buildAdminScene(sceneData) {
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
                            const element = createHotspotElement(hotspot);

                            scene.hotspotContainer().createHotspot(
                                element, {
                                    yaw: hotspot.yaw,
                                    pitch: hotspot.pitch
                                }
                            );
                        });

                        marzipanoScenes.set(sceneId, scene);

                        return scene;
                    }

                    function setActiveSceneItem(sceneId) {
                        sceneItems.forEach(item => {
                            item.classList.toggle(
                                'active',
                                Number(item.dataset.sceneId) === Number(sceneId)
                            );
                        });
                    }

                    function escapeHtml(value) {
                        return String(value ?? '')
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#039;');
                    }

                    function setFieldValue(form, name, value) {
                        const field = form?.querySelector(`[name="${name}"]`);

                        if (!field) return;

                        if (field.type === 'checkbox') {
                            field.checked = Boolean(value);
                            return;
                        }

                        if (field.type !== 'file') {
                            field.value = value ?? '';
                        }
                    }

                    function syncSceneForm(form, sceneData) {
                        if (!form) return;

                        form.action = sceneData.updateUrl;
                        setFieldValue(form, 'nama_lokasi', sceneData.namaLokasi);
                        setFieldValue(form, 'deskripsi', sceneData.deskripsi);
                        setFieldValue(form, 'status', sceneData.status);
                        setFieldValue(form, 'urutan', sceneData.urutan);
                        setFieldValue(form, 'thumbnail', sceneData.thumbnail);
                        setFieldValue(form, 'initial_yaw', sceneData.initialViewDegrees.yaw);
                        setFieldValue(form, 'initial_pitch', sceneData.initialViewDegrees.pitch);
                        setFieldValue(form, 'initial_fov', sceneData.initialViewDegrees.fov);
                        setFieldValue(form, 'show_on_landing', sceneData.showOnLanding);

                        const panoramaLabel = form.querySelector('[data-panorama-file]');

                        if (panoramaLabel) {
                            panoramaLabel.style.display = sceneData.panoramaName ? '' : 'none';
                            panoramaLabel.textContent = sceneData.panoramaName ?
                                `File saat ini: ${sceneData.panoramaName}` :
                                '';
                        }
                    }

                    function syncPublishForm(sceneData) {
                        if (!publishSceneForm) return;

                        publishSceneForm.action = sceneData.updateUrl;
                        setFieldValue(publishSceneForm, 'nama_lokasi', sceneData.namaLokasi);
                        setFieldValue(publishSceneForm, 'deskripsi', sceneData.deskripsi);
                        setFieldValue(publishSceneForm, 'urutan', sceneData.urutan);
                        setFieldValue(publishSceneForm, 'initial_yaw', sceneData.initialViewDegrees.yaw);
                        setFieldValue(publishSceneForm, 'initial_pitch', sceneData.initialViewDegrees.pitch);
                        setFieldValue(publishSceneForm, 'initial_fov', sceneData.initialViewDegrees.fov);
                        setFieldValue(publishSceneForm, 'thumbnail', sceneData.thumbnail);

                        let landingInput = publishSceneForm.querySelector('[name="show_on_landing"]');

                        if (sceneData.showOnLanding && !landingInput) {
                            landingInput = document.createElement('input');
                            landingInput.type = 'hidden';
                            landingInput.name = 'show_on_landing';
                            publishSceneForm.appendChild(landingInput);
                        }

                        if (landingInput) {
                            if (sceneData.showOnLanding) {
                                landingInput.value = '1';
                            } else {
                                landingInput.remove();
                            }
                        }
                    }

                    function syncAddHotspotForm(sceneData) {
                        if (!addHotspotForm) return;

                        addHotspotForm.action = sceneData.storeHotspotUrl;

                        if (addHotspotTitle) {
                            addHotspotTitle.textContent = sceneData.namaLokasi || 'Virtual Tour';
                        }

                        const targetSelect = addHotspotForm.querySelector('[name="target_scene_id"]');

                        if (!targetSelect) return;

                        targetSelect.innerHTML = '<option value="">Tidak ada target</option>';

                        adminTourScenes.forEach(scene => {
                            if (Number(scene.id) === Number(sceneData.id)) return;

                            const option = document.createElement('option');
                            option.value = scene.id;
                            option.textContent = scene.namaLokasi;
                            targetSelect.appendChild(option);
                        });
                    }

                    function toDegree(value) {
                        if (value === null || value === undefined || value === '') return '';

                        return (Number(value) * 180 / Math.PI).toFixed(4).replace(/\.0+$/, '').replace(
                            /(\.\d*?[1-9])0+$/, '$1');
                    }

                    function renderHotspotModals(sceneData) {
                        if (!hotspotModalContainer) return;

                        if (!sceneData.hotspots.length) {
                            hotspotModalContainer.innerHTML = '';
                            return;
                        }

                        hotspotModalContainer.innerHTML = sceneData.hotspots.map(hotspot => {
                            const targetOptions = adminTourScenes
                                .filter(scene => Number(scene.id) !== Number(sceneData.id))
                                .map(scene => `
                                <option value="${scene.id}" ${Number(hotspot.targetSceneId) === Number(scene.id) ? 'selected' : ''}>
                                    ${escapeHtml(scene.namaLokasi)}
                                </option>
                            `)
                                .join('');

                            return `
                            <div class="modal fade admin-modal virtual-tour-modal" id="modalEditHotspot${hotspot.id}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="modal-title-wrap">
                                                <span>${escapeHtml(sceneData.namaLokasi || 'Virtual Tour')}</span>
                                                <h3>Edit Hotspot</h3>
                                            </div>
                                            <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <form method="POST" action="${escapeHtml(hotspot.updateUrl)}" id="formEditHotspotVirtualTour${hotspot.id}" class="virtual-tour-form">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                <div class="form-group">
                                                    <label>Jenis Hotspot</label>
                                                    <select name="tipe" required>
                                                        <option value="navigation" ${hotspot.tipe === 'navigation' ? 'selected' : ''}>Navigation Hotspot</option>
                                                        <option value="information" ${hotspot.tipe === 'information' ? 'selected' : ''}>Information Hotspot</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Target Lokasi</label>
                                                    <select name="target_scene_id">
                                                        <option value="">Tidak ada target</option>
                                                        ${targetOptions}
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Icon Hotspot</label>
                                                    <select name="icon" required>
                                                        <option value="arrow-right" ${hotspot.icon === 'arrow-right' ? 'selected' : ''}>Panah kanan</option>
                                                        <option value="arrow-up" ${hotspot.icon === 'arrow-up' ? 'selected' : ''}>Panah atas</option>
                                                        <option value="arrow-down" ${hotspot.icon === 'arrow-down' ? 'selected' : ''}>Panah bawah</option>
                                                        <option value="arrow-left" ${hotspot.icon === 'arrow-left' ? 'selected' : ''}>Panah kiri</option>
                                                        <option value="info" ${hotspot.icon === 'info' ? 'selected' : ''}>Info</option>
                                                        <option value="door" ${hotspot.icon === 'door' ? 'selected' : ''}>Door</option>
                                                        <option value="camera" ${hotspot.icon === 'camera' ? 'selected' : ''}>Camera</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Judul Informasi</label>
                                                    <input type="text" name="judul" value="${escapeHtml(hotspot.judul)}" placeholder="Contoh: Masjid Utama">
                                                </div>

                                                <div class="form-group full">
                                                    <label>Deskripsi Informasi</label>
                                                    <textarea name="deskripsi" rows="3">${escapeHtml(hotspot.deskripsi)}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label>Yaw Position</label>
                                                    <input type="number" step="0.0001" name="yaw" value="${toDegree(hotspot.yaw)}" required>
                                                    <small>Gunakan derajat (-360 sampai 360). Data tetap disimpan sebagai radian.</small>
                                                </div>

                                                <div class="form-group">
                                                    <label>Pitch Position</label>
                                                    <input type="number" step="0.0001" name="pitch" value="${toDegree(hotspot.pitch)}" required>
                                                    <small>Gunakan derajat (-90 sampai 90). Data tetap disimpan sebagai radian.</small>
                                                </div>

                                                <div class="form-group">
                                                    <label>Yaw Saat Tiba</label>
                                                    <input type="number" step="0.0001" name="target_yaw" value="${toDegree(hotspot.targetYaw)}">
                                                    <small>Opsional untuk hotspot navigasi. Kosongkan agar memakai tampilan awal scene tujuan.</small>
                                                </div>

                                                <div class="form-group">
                                                    <label>Pitch Saat Tiba</label>
                                                    <input type="number" step="0.0001" name="target_pitch" value="${toDegree(hotspot.targetPitch)}">
                                                    <small>Opsional. Gunakan derajat (-90 sampai 90).</small>
                                                </div>

                                                <div class="form-group">
                                                    <label>FOV Saat Tiba</label>
                                                    <input type="number" step="0.0001" name="target_fov" value="${toDegree(hotspot.targetFov)}">
                                                    <small>Opsional. Gunakan derajat (20 sampai 160).</small>
                                                </div>

                                                <div class="form-switch">
                                                    <label class="switch">
                                                        <input type="checkbox" name="is_active" value="1" ${hotspot.isActive ? 'checked' : ''}>
                                                        <span></span>
                                                    </label>

                                                    <p>Tampilkan hotspot</p>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn-cancel" data-dismiss="modal">Batal</button>
                                            <button type="submit" form="formEditHotspotVirtualTour${hotspot.id}" class="btn-save">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                Update Hotspot
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        }).join('');
                    }

                    function renderScenePreview(sceneData) {
                        if (!sceneSettingsPreview) return;

                        if (sceneData.thumbnailIcon) {
                            sceneSettingsPreview.innerHTML = `
                            <div class="scene-preview-icon">
                                <i class="fa-solid ${escapeHtml(sceneData.thumbnailIcon)}"></i>
                            </div>
                        `;
                            return;
                        }

                        sceneSettingsPreview.innerHTML = `
                        <img src="${escapeHtml(sceneData.thumbnailUrl)}" alt="${escapeHtml(sceneData.namaLokasi)}">
                    `;
                    }

                    function renderHotspotList(sceneData) {
                        if (!hotspotList) return;

                        if (!sceneData.hotspots.length) {
                            hotspotList.innerHTML = `
                            <div class="hotspot-item">
                                <div>
                                    <h5>Belum ada hotspot</h5>
                                    <span>Tambahkan titik navigasi atau informasi</span>
                                </div>
                            </div>
                        `;
                            return;
                        }

                        hotspotList.innerHTML = sceneData.hotspots.map(hotspot => {
                            const icon = hotspot.tipe === 'information' ?
                                'fa-circle-info' :
                                navigationIcons[hotspot.icon] || navigationIcons.arrow;
                            const modalId = `modalEditHotspot${hotspot.id}`;
                            const hasModal = Boolean(document.getElementById(modalId));
                            const buttonAttrs = hasModal ?
                                `data-toggle="modal" data-target="#${modalId}"` :
                                'disabled title="Buka ulang halaman scene ini untuk edit hotspot"';

                            return `
                            <div class="hotspot-item">
                                <div class="hotspot-icon ${hotspot.tipe === 'information' ? 'info' : ''}">
                                    <i class="fa-solid ${escapeHtml(icon)}"></i>
                                </div>

                                <div>
                                    <h5>${escapeHtml(hotspot.tipe.charAt(0).toUpperCase() + hotspot.tipe.slice(1))}</h5>
                                    <span>${escapeHtml(hotspot.label)}</span>
                                </div>

                                <div class="hotspot-actions">
                                    <button type="button" class="hotspot-btn" ${buttonAttrs}>
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button type="button" class="hotspot-btn delete" data-toggle="modal"
                                        data-target="#modalHapusHotspot" data-delete-hotspot-url="${escapeHtml(hotspot.destroyUrl)}"
                                        data-hotspot-label="${escapeHtml(hotspot.label)}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        }).join('');
                    }

                    function updateAdminInspector(sceneData) {
                        if (topbarTitle) topbarTitle.textContent = sceneData.namaLokasi || 'Virtual Tour';
                        if (topbarSubtitle) topbarSubtitle.textContent = 'Kelola panorama dan hotspot lokasi ini';

                        syncSceneForm(sceneSettingsForm, sceneData);
                        syncSceneForm(editSceneForm, sceneData);
                        syncPublishForm(sceneData);
                        syncAddHotspotForm(sceneData);
                        renderScenePreview(sceneData);
                        renderHotspotModals(sceneData);
                        renderHotspotList(sceneData);

                        if (deleteSceneForm) {
                            deleteSceneForm.action = sceneData.destroyUrl;
                        }

                        if (deleteSceneName) {
                            deleteSceneName.textContent = sceneData.namaLokasi || 'scene ini';
                        }
                    }

                    document.addEventListener('click', function(event) {
                        const deleteButton = event.target.closest('[data-delete-hotspot-url]');

                        if (!deleteButton) return;

                        if (deleteHotspotForm) {
                            deleteHotspotForm.action = deleteButton.dataset.deleteHotspotUrl;
                        }

                        if (deleteHotspotName) {
                            deleteHotspotName.textContent = deleteButton.dataset.hotspotLabel || 'hotspot ini';
                        }
                    });

                    function updateSceneChrome(sceneData, sceneId, shouldPushState, arrivalView) {
                        setActiveSceneItem(sceneId);
                        updateAdminInspector(sceneData);

                        if (viewerTitle) {
                            viewerTitle.textContent = sceneData.namaLokasi || 'Panorama Viewer';
                        }

                        if (viewerPositionScene) {
                            viewerPositionScene.textContent = `Scene : ${sceneData.namaLokasi || '-'}`;
                        }

                        if (viewerPositionHotspots) {
                            viewerPositionHotspots.textContent = `Hotspot : ${sceneData.hotspots.length}`;
                        }

                        if (shouldPushState) {
                            history.pushState({
                                sceneId,
                                arrivalView
                            }, '', sceneData.url);
                        }
                    }

                    function switchAdminScene(sceneId, shouldPushState = false, arrivalView = null) {
                        const numericSceneId = Number(sceneId);
                        const sceneData = sceneDataById.get(numericSceneId);

                        if (!sceneData) return false;

                        const nextScene = buildAdminScene(sceneData);

                        if (!nextScene) {
                            if (emptyPanorama) emptyPanorama.style.display = '';

                            updateSceneChrome(sceneData, numericSceneId, shouldPushState, arrivalView);

                            return true;
                        }

                        if (emptyPanorama) emptyPanorama.style.display = 'none';

                        nextScene.view().setParameters(resolveView(sceneData, arrivalView));
                        nextScene.switchTo({
                            transitionDuration: 450
                        });
                        currentMarzipanoScene = nextScene;

                        updateSceneChrome(sceneData, numericSceneId, shouldPushState, arrivalView);

                        return true;
                    }

                    sceneItems.forEach(item => {
                        item.addEventListener('click', function(event) {
                            if (!this.dataset.sceneId) return;

                            if (switchAdminScene(this.dataset.sceneId, true)) {
                                event.preventDefault();
                            }
                        });
                    });

                    window.addEventListener('popstate', function(event) {
                        const params = new URLSearchParams(window.location.search);
                        const sceneId = event.state?.sceneId || params.get('scene') || activeSceneId;

                        switchAdminScene(sceneId, false, event.state?.arrivalView || null);
                    });

                    history.replaceState({
                        sceneId: Number(activeSceneId),
                        arrivalView: null
                    }, '', window.location.href);

                    switchAdminScene(activeSceneId, false, initialView);
                }
            });
        </script>
    @endpush
@endsection
