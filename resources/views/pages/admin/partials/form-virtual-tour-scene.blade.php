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
    @if ($scene?->panorama)
        <small>File saat ini: {{ basename($scene->panorama) }}</small>
    @endif
</div>

<div class="form-switch">
    <label class="switch">
        <input type="checkbox" name="show_on_landing" value="1"
            {{ old('show_on_landing', $scene->show_on_landing ?? false) ? 'checked' : '' }}>
        <span></span>
    </label>

    <p>Jadikan sebagai menu lokasi di landing page</p>
</div>
