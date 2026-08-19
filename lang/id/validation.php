<?php

return [
    'required' => ':attribute wajib diisi.',
    'required_if' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'numeric' => ':attribute harus berupa angka.',
    'boolean' => ':attribute harus bernilai benar atau salah.',
    'array' => ':attribute harus berupa daftar.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'date' => ':attribute harus berupa tanggal yang valid.',
    'in' => 'Nilai :attribute yang dipilih tidak valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'unique' => ':attribute sudah terdaftar.',
    'file' => ':attribute harus berupa berkas.',
    'image' => ':attribute harus berupa gambar.',
    'mimes' => ':attribute harus berupa berkas dengan format: :values.',
    'max' => ['file' => 'Ukuran :attribute tidak boleh lebih dari :max KB.', 'string' => ':attribute tidak boleh lebih dari :max karakter.', 'numeric' => ':attribute tidak boleh lebih dari :max.'],
    'min' => ['file' => 'Ukuran :attribute harus minimal :min KB.', 'string' => ':attribute harus minimal :min karakter.', 'numeric' => ':attribute harus minimal :min.'],
    'attributes' => ['nisn' => 'NISN', 'nama_lengkap' => 'nama lengkap', 'jenis_kelamin' => 'jenis kelamin', 'payment_method' => 'metode pembayaran', 'email' => 'email', 'password' => 'kata sandi'],
];
