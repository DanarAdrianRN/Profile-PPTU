@php
    $hotspot = $hotspot ?? null;
@endphp

<div class="form-group">
    <label>Jenis Hotspot</label>
    <select name="tipe" required>
        <option value="navigation" {{ old('tipe', $hotspot->tipe ?? 'navigation') === 'navigation' ? 'selected' : '' }}>
            Navigation Hotspot
        </option>
        <option value="information" {{ old('tipe', $hotspot->tipe ?? '') === 'information' ? 'selected' : '' }}>
            Information Hotspot
        </option>
    </select>
</div>

<div class="form-group">
    <label>Target Lokasi</label>
    <select name="target_scene_id">
        <option value="">Tidak ada target</option>
        @foreach ($allScenes as $sceneOption)
            @if ($sceneOption->id !== $activeScene->id)
                <option value="{{ $sceneOption->id }}"
                    {{ (int) old('target_scene_id', $hotspot->target_scene_id ?? 0) === $sceneOption->id ? 'selected' : '' }}>
                    {{ $sceneOption->nama_lokasi }}
                </option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Icon Hotspot</label>
    <select name="icon" required>
        <option value="arrow-right"
            {{ old('icon', $hotspot->icon ?? 'arrow-right') === 'arrow-right' ? 'selected' : '' }}>Panah kanan</option>
        <option value="arrow-up" {{ old('icon', $hotspot->icon ?? '') === 'arrow-up' ? 'selected' : '' }}>Panah atas
        </option>
        <option value="arrow-down" {{ old('icon', $hotspot->icon ?? '') === 'arrow-down' ? 'selected' : '' }}>Panah
            bawah</option>
        <option value="arrow-left" {{ old('icon', $hotspot->icon ?? '') === 'arrow-left' ? 'selected' : '' }}>Panah kiri
        </option>
        <option value="info" {{ old('icon', $hotspot->icon ?? '') === 'info' ? 'selected' : '' }}>Info</option>
        <option value="door" {{ old('icon', $hotspot->icon ?? '') === 'door' ? 'selected' : '' }}>Door</option>
        <option value="camera" {{ old('icon', $hotspot->icon ?? '') === 'camera' ? 'selected' : '' }}>Camera</option>
    </select>
</div>

<div class="form-group">
    <label>Judul Informasi</label>
    <input type="text" name="judul" value="{{ old('judul', $hotspot->judul ?? '') }}"
        placeholder="Contoh: Masjid Utama">
</div>

<div class="form-group full">
    <label>Deskripsi Informasi</label>
    <textarea name="deskripsi" rows="3">{{ old('deskripsi', $hotspot->deskripsi ?? '') }}</textarea>
</div>

<div class="form-group">
    <label>Yaw Position</label>
    <input type="number" step="0.0001" name="yaw" value="{{ old('yaw', $hotspot->yaw ?? 0) }}" required>
    <small>Bisa memakai derajat (-360 sampai 360) atau radian.</small>
</div>

<div class="form-group">
    <label>Pitch Position</label>
    <input type="number" step="0.0001" name="pitch" value="{{ old('pitch', $hotspot->pitch ?? 0) }}" required>
    <small>Bisa memakai derajat (-90 sampai 90) atau radian.</small>
</div>

<div class="form-switch">
    <label class="switch">
        <input type="checkbox" name="is_active" value="1"
            {{ old('is_active', $hotspot->is_active ?? true) ? 'checked' : '' }}>
        <span></span>
    </label>

    <p>Tampilkan hotspot</p>
</div>
