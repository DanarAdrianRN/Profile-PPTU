@extends('layout.app')

@section('content')
    <div class="admin-layout">
        {{-- SIDEBAR --}}
        @include('components.sidebar-admin')
        <div class="admin-main">
            {{-- HEADER --}}
            @include('components.header-admin', ['title' => 'Manajemen Pendaftaran'])
            <section class="pendaftaran-admin">
                {{-- STATISTICS --}}
                <div class="pendaftaran-stats">
                    <div class="stat-card">
                        <div class="icon blue">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="info">
                            <span>
                                Total Pendaftar
                            </span>
                            <h3>
                                {{ $jumlahPendaftaran }}
                            </h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon yellow">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="info">
                            <span>
                                Menunggu Verifikasi
                            </span>
                            <h3>
                                {{ $menungguVerifikasi }}
                            </h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon green">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div class="info">
                            <span>
                                Diterima
                            </span>
                            <h3>
                                {{ $diterima }}
                            </h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon red">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                        <div class="info">
                            <span>
                                Ditolak
                            </span>
                            <h3>
                                {{ $ditolak }}
                            </h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon teal">
                            <i class="fa-money-bill-wave"></i>
                        </div>
                        <div class="info">
                            <span>
                                Lunas
                            </span>
                            <h3>
                                {{ $lunas }}
                            </h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="icon orange">
                            <i class="fa-money-bill-wave"></i>
                        </div>
                        <div class="info">
                            <span>
                                Belum Lunas
                            </span>
                            <h3>
                                {{ $belumLunas }}
                            </h3>
                        </div>
                    </div>
                </div>
                {{-- FILTER BAR --}}
                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Cari nama pendaftar...">
                    </div>
                    <div class="filter-group">
                        <div class="select-wrapper">
                            <select
                                title="{{ $selectedPeriode?->nama_periode ?? 'Pilih periode' }}"
                                style="width: 220px; max-width: 220px;"
                                onchange="window.location.href='{{ route('admin-pendaftaran') }}?periode_id=' + this.value">
                                @foreach ($periodes as $periode)
                                    <option value="{{ $periode->id }}"
                                        title="{{ $periode->nama_periode }}"
                                        @selected((int) $selectedPeriodeId === $periode->id)>
                                        {{ \Illuminate\Support\Str::limit($periode->nama_periode, 28) }}
                                        {{ $periode->is_active ? ' - Aktif' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="select-wrapper">
                            <select>
                                <option>Semua Jenjang</option>
                                <option>SMP</option>
                                <option>SMK</option>
                            </select>
                        </div>
                        <div class="select-wrapper">
                        <select>
                            <option>Semua Pembayaran</option>
                            <option>Belum Bayar</option>
                            <option>Lunas</option>
                            <option>DP</option>
                        </select>
                        </div>
                    </div>
                    <button class="btn-add">
                        <i class="fa-solid fa-file-export"></i>
                        Export
                    </button>
                </div>
                {{-- TABLE --}}
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="pendaftaran-table">
                            <thead>
                                <tr>

                                    <th>No</th>

                                    <th>Pendaftar</th>

                                    <th>Jenjang</th>

                                    <th>Jurusan</th>

                                    <th>Wali Santri</th>

                                    <th>Pembayaran</th>

                                    <th>Tanggal Daftar</th>

                                    <th>Status</th>

                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            @foreach ($pendaftarans as $key => $pendaftaran)
                                <tr>
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>

                                        <div class="student-cell">

                                            <div class="student-photo">

                                                @php
                                                    $foto = $pendaftaran->dokumens
                                                        ->where('jenis_dokumen', 'Foto Warna')
                                                        ->first();
                                                @endphp

                                                <img src="{{ $foto ? asset('storage/' . $foto->file) : asset('assets/galeri1.jpg') }}"
                                                    alt="{{ $pendaftaran->nama_lengkap }}">

                                            </div>

                                            <div class="student-info">

                                                <h5>
                                                    {{ $pendaftaran->nama_lengkap }}
                                                </h5>

                                                <span>
                                                    NISN :
                                                    {{ $pendaftaran->pendidikan->nisn ?? '-' }}
                                                </span>

                                            </div>

                                        </div>

                                    </td>
                                    <td>

                                        <span class="badge blue">
                                            {{ $pendaftaran->pendidikan->jenjang_pendidikan ?? '-' }}
                                        </span>

                                    </td>
                                    <td>
                                        {{ $pendaftaran->pendidikan->jurusan ?? '-' }}
                                    </td>
                                    <td>

                                        @php
                                            $ayah = $pendaftaran->orangTuas->where('tipe', 'ayah')->first();
                                        @endphp

                                        {{ $ayah->nama ?? '-' }}

                                    </td>
                                    <td>
                                        @php
                                            $tagihan = $pendaftaran->tagihanSantri;

                                            $totalTagihan = $tagihan?->details?->count() ?? 0;

                                            $totalLunas = $tagihan?->details
                                                ?->where('status_pembayaran', 'lunas')
                                                ->count() ?? 0;

                                            $isLunas = $totalTagihan > 0 && $totalTagihan === $totalLunas;
                                        @endphp

                                        <button
                                            class="payment-badge {{ $isLunas ? 'paid' : 'unpaid' }}"
                                            data-toggle="modal"
                                            data-target="#modalBayar{{ $pendaftaran->id }}"
                                        >
                                            {{ $isLunas ? 'Lunas' : 'Belum Lunas' }}
                                        </button>
                                    </td>
                                    <td>
                                        {{ $pendaftaran->created_at->translatedFormat('d M Y') }}
                                    </td>
                                    <td>
                                        <div class="status-dropdown">

                                            <button class="payment-badge paid">

                                                {{ ucfirst(str_replace('_', ' ', $pendaftaran->status)) }}
                                            </button>

                                            @if ($canManageSelectedPeriod)
                                                <div class="status-dropdown-menu">

                                                    <form
                                                        action="{{ route('pendaftaran.update-status', $pendaftaran->id) }}"
                                                        method="POST">

                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            name="status"
                                                            value="menunggu_verifikasi">

                                                            Menunggu Verifikasi

                                                        </button>

                                                        <button
                                                            type="submit"
                                                            name="status"
                                                            value="diterima">

                                                            Diterima

                                                        </button>

                                                        <button
                                                            type="submit"
                                                            name="status"
                                                            value="ditolak">

                                                            Ditolak

                                                        </button>

                                                    </form>

                                                </div>
                                            @endif

                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-action">
                                            <button class="btn-action view" data-toggle="modal"
                                                data-target="#modalPrintPendaftaran{{$pendaftaran->id}}">
                                                <i class="fa-solid fa-print"></i>
                                            </button>
                                            @if ($canManageSelectedPeriod)
                                                <button class="btn-action edit" data-toggle="modal"
                                                    data-target="#modalEditPendaftaran{{$pendaftaran->id}}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </button>
                                                <button class="btn-action delete" data-toggle="modal" data-target="#modalHapusPendaftaran{{$pendaftaran->id}}">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
