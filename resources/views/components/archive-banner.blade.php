@if (($isArsip ?? false) && $periode)
    <div class="archive-banner">
        <div class="archive-banner-text">
            <i class="fa-solid fa-box-archive"></i>
            <span>Sedang menampilkan data arsip periode: <strong>{{ $periode->nama_periode }}</strong></span>
        </div>
        <a href="{{ route('periode.keluar-arsip') }}" class="archive-banner-exit">
            Kembali ke Periode Aktif
        </a>
    </div>
@endif