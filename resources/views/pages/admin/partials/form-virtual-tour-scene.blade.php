@php
    $scene = $scene ?? null;
    $thumbnailIcons = [
        'building' => ['label' => 'Gedung', 'icon' => 'fa-building'],
        'mosque' => ['label' => 'Masjid', 'icon' => 'fa-mosque'],
        'road' => ['label' => 'Jalan', 'icon' => 'fa-road'],
        'field' => ['label' => 'Lapangan', 'icon' => 'fa-futbol'],
        'home' => ['label' => 'Rumah', 'icon' => 'fa-house'],
    ];
    $selectedThumbnail = old('thumbnail', array_key_exists($scene->thumbnail ?? '', $thumbnailIcons) ? $scene->thumbnail : 'building');
@endphp

<div class="form-group">
    <label>Nama Lokasi</label>
    <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi', $scene->nama_lokasi ?? '') }}" required>
</div>

<div class="form-group full">
    <label>Deskripsi</label>
    <textarea name="deskripsi" rows="4">{{ old('deskripsi', $scene->deskripsi ?? '') }}</textarea>
</div>

<div class="form-group">
    <label>Status Scene</label>
    <select name="status" required>
        <option value="published" {{ old('status', $scene->status ?? '') === 'published' ? 'selected' : '' }}>
            Published
        </option>
        <option value="draft" {{ old('status', $scene->status ?? 'draft') === 'draft' ? 'selected' : '' }}>
            Draft
        </option>
        <option value="hidden" {{ old('status', $scene->status ?? '') === 'hidden' ? 'selected' : '' }}>
            Hidden
        </option>
    </select>
</div>

<div class="form-group">
    <label>Urutan Lokasi</label>
    <input type="number" name="urutan" value="{{ old('urutan', $scene->urutan ?? 0) }}" min="0">
</div>

<div class="form-group">
    <label>Yaw Tampilan Awal</label>
    <input type="number" step="0.0001" name="initial_yaw"
        value="{{ old('initial_yaw', $scene?->initial_yaw_degree ?? 0) }}">
    <small>Gunakan derajat (-360 sampai 360). Data tetap disimpan sebagai radian.</small>
</div>

<div class="form-group">
    <label>Pitch Tampilan Awal</label>
    <input type="number" step="0.0001" name="initial_pitch"
        value="{{ old('initial_pitch', $scene?->initial_pitch_degree ?? 0) }}">
    <small>Gunakan derajat (-90 sampai 90). Data tetap disimpan sebagai radian.</small>
</div>

<div class="form-group">
    <label>FOV Tampilan Awal</label>
    <input type="number" step="0.0001" name="initial_fov"
        value="{{ old('initial_fov', $scene?->initial_fov_degree ?? 90) }}">
    <small>Gunakan derajat (20 sampai 160). Kosongkan untuk 90 derajat.</small>
</div>

<div class="form-group full">
    <label>Icon Lokasi</label>
    <select name="thumbnail" required>
        @foreach ($thumbnailIcons as $value => $icon)
            <option value="{{ $value }}" {{ $selectedThumbnail === $value ? 'selected' : '' }}>
                {{ $icon['label'] }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Upload Panorama 360°</label>
    <input type="file" name="panorama" accept="image/*">
    <small data-panorama-file style="{{ $scene?->panorama ? '' : 'display: none;' }}">
        File saat ini: {{ $scene?->panorama ? basename($scene->panorama) : '' }}
    </small>
</div>

<div class="form-switch">
    <label class="switch">
        <input type="checkbox" name="show_on_landing" value="1"
            {{ old('show_on_landing', $scene->show_on_landing ?? false) ? 'checked' : '' }}>
        <span></span>
    </label>

    <p>Jadikan sebagai menu lokasi di landing page</p>
</div>
