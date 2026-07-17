@php
    $formAdmin = $formAdmin ?? null;
    $mode = $mode ?? 'create';
    $isEdit = $mode === 'edit';
@endphp

<div class="form-group">
    <label>Nama Lengkap</label>
    <input type="text" name="nama_lengkap" placeholder="Masukkan nama admin"
        value="{{ old('nama_lengkap', $formAdmin?->nama_lengkap) }}" required>
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" placeholder="Masukkan email admin"
        value="{{ old('email', $formAdmin?->email) }}" required>
</div>

<div class="form-group">
    <label>Username</label>
    <input type="text" name="username" placeholder="Masukkan username admin"
        value="{{ old('username', $formAdmin?->username) }}" required>
</div>

<div class="form-group">
    <label>Role</label>
    <select name="role" required>
        <option value="" disabled {{ old('role', $formAdmin?->role) ? '' : 'selected' }}>
            Pilih role
        </option>
        <option value="administrasi" {{ old('role', $formAdmin?->role) === 'administrasi' ? 'selected' : '' }}>
            Administrasi
        </option>
        <option value="media" {{ old('role', $formAdmin?->role) === 'media' ? 'selected' : '' }}>
            Media
        </option>
    </select>
</div>

<div class="form-group full">
    <label>Password</label>
    <input type="password" name="password"
        placeholder="{{ $isEdit ? 'Kosongkan jika tidak ingin mengganti password' : 'Masukkan password admin' }}"
        {{ $isEdit ? '' : 'required' }}>
</div>
