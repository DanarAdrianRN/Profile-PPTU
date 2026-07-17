@extends('layout.app')

@section('content')
    <div class="admin-layout">

        {{-- SIDEBAR --}}
        @include('components.sidebar-admin')

        <div class="admin-main">

            {{-- HEADER --}}
            @include('components.header-admin', ['title' => 'Manajemen Biaya Pendaftaran'])

            <div class="admin-biaya-pendaftaran">

                {{-- FILTER --}}
                <div class="filter-wrapper">

                    <div class="search-box">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input type="text" placeholder="Cari biaya pendaftaran...">

                    </div>

                    <div class="filter-group">

                        <select>
                            <option>Semua Jenjang</option>
                            <option>SMP</option>
                            <option>SMK</option>
                        </select>

                        <select>
                            <option>Semua</option>
                            <option>Tahunan</option>
                            <option>Bulanan</option>
                        </select>

                        
                    </div>
                    <button class="btn-add" data-toggle="modal" data-target="#modalTambahBiaya">
    
                        <i class="fa-solid fa-plus"></i>
    
                        Tambah Biaya
    
                    </button>
                </div>

                {{-- TABLE --}}
                <div class="table-wrapper">
    
                    <table>
    
                        <thead>
    
                            <tr>
    
                                <th width="5%">No</th>
    
                                <th>Jenjang</th>
    
                                <th>Nama Biaya</th>
    
                                <th>Kategori</th>
    
                                <th>Total</th>
    
                                <th width="15%">Aksi</th>
    
                            </tr>
    
                        </thead>
    
                        <tbody>
                            @for ($i = 1; $i <= 8; $i++)
                                <tr>
    
                                    <td> {{ $i }}</td>
    
                                    <td>
                                        <div class="jenjang-badge smp">
                                            SMP
                                        </div>
                                    </td>
    
                                    <td>
    
                                        <div class="biaya-info">
    
                                            <h5>
                                                Registrasi Pondok
                                            </h5>
    
                                            <span>
                                                Biaya awal pendaftaran
                                            </span>
    
                                        </div>
    
                                    </td>
    
                                    <td>
                                        <span class="kategori awal">
                                            Tahunan
                                        </span>
                                    </td>
    
                                    <td>
                                        Rp 60.000
                                    </td>
    
                                    <td>
    
                                        <div class="table-action">
    
                                            <button class="btn-action view" data-toggle="modal" data-target="#modalViewBiaya">
    
                                                <i class="fa-regular fa-eye"></i>
    
                                            </button>
    
                                            <button class="btn-action edit" data-toggle="modal" data-target="#modalEditBiaya">
    
                                                <i class="fa-regular fa-pen-to-square"></i>
    
                                            </button>
    
                                            <button class="btn-action delete" data-toggle="modal" data-target="#modalHapus">
    
                                                <i class="fa-regular fa-trash-can"></i>
    
                                            </button>
    
                                        </div>
    
                                    </td>
    
                                </tr>
                            @endfor
    
                        </tbody>
    
                    </table>
    
                </div>
            </div>


        </div>
    </div>
@endsection
