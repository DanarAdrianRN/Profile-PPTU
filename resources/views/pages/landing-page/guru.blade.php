@extends('layout.app')
@include('components.header')
@section('content')
    @include('components.hero', [
        'badge' => 'Tenaga Pendidik',
        'title' => 'Guru & Ustadz',
        'titlespan' => 'PPTU',
        'subtitle' => 'Pengajar profesional dan berpengalaman dalam pendidikan formal maupun kepesantrenan',
    ])
        <section class="statistik">
            <div class="container">
                <div class="stat-grid">
                    {{-- TOTAL PENGAJAR --}}
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <h2>
                            {{ $totalPengajar }}+
                        </h2>
                        <p>
                            Total Pengajar
                        </p>
                    </div>
                    {{-- GURU SMP --}}
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <h2>
                            {{ $guruSMP }}+
                        </h2>
                        <p>
                            Guru SMP
                        </p>
                    </div>
                    {{-- GURU SMK --}}
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fa-solid fa-school"></i>
                        </div>
                        <h2>
                            {{ $guruSMK }}+
                        </h2>
                        <p>
                            Guru SMK
                        </p>
                    </div>
                    {{-- USTADZ MADIN --}}
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <h2>
                            {{ $ustadzTPQ }}+
                        </h2>
                        <p>
                            Ustadz TPQ
                        </p>
                    </div>
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <h2>
                            {{ $ustadzMadqur }}+
                        </h2>
                        <p>
                            Ustadz Madqur
                        </p>
                    </div>
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <h2>
                            {{ $ustadzMadin }}+
                        </h2>
                        <p>
                            Ustadz Madin
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="guru-section">
            <div class="container">
                {{-- TAB --}}
                <div class="nav guru-tabs"
                    id="guruTab"
                    role="tablist">
                    <button class="tab-btn active"
                        id="madin-tab"
                        data-toggle="tab"
                        data-target="#madin"
                        type="button">
                        Madrasah Diniyah
                    </button>
                    <button class="tab-btn"
                        id="smp-tab"
                        data-toggle="tab"
                        data-target="#smp"
                        type="button">
                        SMP
                    </button>
                    <button class="tab-btn"
                        id="smk-tab"
                        data-toggle="tab"
                        data-target="#smk"
                        type="button">
                        SMK
                    </button>
                     <button class="tab-btn"
                        id="madqur-tab"
                        data-toggle="tab"
                        data-target="#madqur"
                        type="button">
                        Madrasah Al-Qur'an
                    </button>
                    <button class="tab-btn"
                        id="tpq-tab"
                        data-toggle="tab"
                        data-target="#tpq"
                        type="button">
                        Taman Pendidikan Al-Qur'an
                    </button>
                </div>
                {{-- TAB CONTENT --}}
                <div class="tab-content mt-5"
                    id="guruTabContent">
                    {{-- MADIN --}}
                    <div class="tab-pane fade show active"
                        id="madin"
                        role="tabpanel">
                        <div class="guru-grid">
                            @forelse($gurus->where('kategori', 'Madrasah Diniyah') as $guru)
                            <div class="guru-card">
                                <div class="guru-image">
                                    <img src="{{ asset('storage/' . $guru->foto) }}"
                                        alt="{{ $guru->nama_lengkap }}">
                                </div>
                                <div class="guru-info">
                                    <h3>
                                        {{ $guru->nama_lengkap }}
                                    </h3>
                                    <div class="meta">
                                        <span>
                                            <i class="fa-solid fa-graduation-cap"></i>
                                            {{ $guru->pendidikan }}
                                        </span>
                                        <span>
                                            <i class="fa-solid fa-book"></i>
                                            {{ $guru->mapel_bidang }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p>
                                Data guru Madrasah Diniyah belum tersedia.
                            </p>
                            @endforelse
                        </div>
                    </div>
                    {{-- SMP --}}
                    <div class="tab-pane fade"
                        id="smp"
                        role="tabpanel">
                        <div class="guru-grid">
                            @forelse($gurus->where('kategori', 'SMP') as $guru)
                            <div class="guru-card">
                                <div class="guru-image">
                                    <img src="{{ asset('storage/' . $guru->foto) }}"
                                        alt="{{ $guru->nama_lengkap }}">
                                </div>
                                <div class="guru-info">
                                    <h3>
                                        {{ $guru->nama_lengkap }}
                                    </h3>
                                    <div class="meta">
                                        <span>
                                            <i class="fa-solid fa-school"></i>
                                            {{ $guru->pendidikan }}
                                        </span>
                                        <span>
                                            <i class="fa-solid fa-book-open"></i>
                                            {{ $guru->mapel_bidang }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p>
                                Data guru SMP belum tersedia.
                            </p>
                            @endforelse
                        </div>
                    </div>
                    {{-- SMK --}}
                    <div class="tab-pane fade"
                        id="smk"
                        role="tabpanel">
                        <div class="guru-grid">
                            @forelse($gurus->where('kategori', 'SMK') as $guru)
                            <div class="guru-card">
                                <div class="guru-image">
                                    <img src="{{ asset('storage/' . $guru->foto) }}"
                                        alt="{{ $guru->nama_lengkap }}">
                                </div>
                                <div class="guru-info">
                                    <h3>
                                        {{ $guru->nama_lengkap }}
                                    </h3>
                                    <div class="meta">
                                        <span>
                                            <i class="fa-solid fa-school"></i>
                                            {{ $guru->pendidikan }}
                                        </span>
                                        <span>
                                            <i class="fa-solid fa-book-open"></i>
                                            {{ $guru->mapel_bidang }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p>
                                Data guru SMK belum tersedia.
                            </p>
                            @endforelse
                        </div>
                    </div>
                    {{-- MADQUR --}}
                    <div class="tab-pane fade"
                        id="madqur"
                        role="tabpanel">
                        <div class="guru-grid">
                            @forelse($gurus->where('kategori', "Madrasah Al-Qur'an") as $guru)
                            <div class="guru-card">
                                <div class="guru-image">
                                    <img src="{{ asset('storage/' . $guru->foto) }}"
                                        alt="{{ $guru->nama_lengkap }}">
                                </div>
                                <div class="guru-info">
                                    <h3>
                                        {{ $guru->nama_lengkap }}
                                    </h3>
                                    <div class="meta">
                                        <span>
                                            <i class="fa-solid fa-graduation-cap"></i>
                                            {{ $guru->pendidikan }}
                                        </span>
                                        <span>
                                            <i class="fa-solid fa-book-quran"></i>
                                            {{ $guru->mapel_bidang }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p>
                                Data guru Madrasah Al-Qur'an belum tersedia.
                            </p>
                            @endforelse
                        </div>
                    </div>
                    {{-- TPQ --}}
                    <div class="tab-pane fade"
                        id="tpq"
                        role="tabpanel">
                        <div class="guru-grid">
                            @forelse($gurus->where('kategori', 'TPQ') as $guru)
                            <div class="guru-card">
                                <div class="guru-image">
                                    <img src="{{ asset('storage/' . $guru->foto) }}"
                                        alt="{{ $guru->nama_lengkap }}">
                                </div>
                                <div class="guru-info">
                                    <h3>
                                        {{ $guru->nama_lengkap }}
                                    </h3>
                                    <div class="meta">
                                        <span>
                                            <i class="fa-solid fa-graduation-cap"></i>
                                            {{ $guru->pendidikan }}
                                        </span>
                                        <span>
                                            <i class="fa-solid fa-book-open-reader"></i>
                                            {{ $guru->mapel_bidang }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p>
                                Data guru TPQ belum tersedia.
                            </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @include('components.footer')
@endsection
