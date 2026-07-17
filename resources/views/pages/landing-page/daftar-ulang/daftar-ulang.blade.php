@extends('layout.app')
@include('components.header')

@section('content')
    <section class="daftar-ulang-page">
        <div class="container">
            <div class="page-header">
                <div class="header-badge">
                    <i class="fa-solid fa-graduation-cap"></i>
                    Daftar Ulang Santri Baru
                </div>

                <h2>Pembayaran Daftar Ulang</h2>

                <p>
                    Pilih tagihan yang ingin dibayarkan untuk melanjutkan proses daftar ulang santri
                </p>
            </div>

            <div class="du-header">
                <div class="header-left">
                    <h3>Informasi Santri</h3>
                    <p>Pastikan data santri sudah sesuai sebelum melakukan pembayaran</p>
                </div>

                <div class="header-right">
                    <span>{{ $pendaftaran->pendidikan?->jenjang_pendidikan ?? 'Santri' }}</span>
                    <h4>{{ $pendaftaran->nama_lengkap }}</h4>
                    <small>NISN : {{ $pendaftaran->pendidikan?->nisn ?? '-' }}</small>
                </div>
            </div>

            @error('tagihan')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            <form action="{{ route('daftar-ulang.bayar', $pendaftaran->id) }}"
                method="POST"
                id="formDaftarUlang">
                @csrf

                <div class="du-card">
                    <div class="card-title">
                        <i class="fa-solid fa-money-bill-wave"></i>
                        <h4>Pilih Tagihan</h4>
                    </div>

                    <div class="bill-list">
                        @foreach ($tagihan->details->groupBy('kategori') as $kategori => $details)
                            <div class="bill-category-title">
                                {{ $kategori }}
                            </div>

                            @foreach ($details as $detail)
                                @php
                                    $isLunas = $detail->status_pembayaran === 'lunas';
                                    $isPending = $detail->status_pembayaran === 'pending';
                                    $isLocked = $isLunas || $isPending;
                                @endphp

                                <label class="bill-item {{ $isLocked ? 'disabled' : '' }} {{ $isLunas ? 'active' : '' }}">
                                    <div class="bill-left">
                                        <input type="checkbox"
                                            name="tagihan_detail_ids[]"
                                            value="{{ $detail->id }}"
                                            class="bill-check"
                                            data-price="{{ $detail->nominal_akhir }}"
                                            data-original="{{ $detail->nominal_awal }}"
                                            data-discount="{{ $detail->potongan_promo }}"
                                            @checked($isLunas)
                                            @disabled($isLocked)>

                                        <div>
                                            <h5>{{ $detail->nama_pembayaran }}</h5>

                                            <p>
                                                {{ $detail->kategori }}
                                                @if ($detail->nama_promo)
                                                    - {{ $detail->nama_promo }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="bill-right">
                                        @if ($detail->potongan_promo > 0)
                                            <small>
                                                <s>Rp {{ number_format($detail->nominal_awal, 0, ',', '.') }}</s>
                                            </small>
                                        @endif

                                        <strong>
                                            Rp {{ number_format($detail->nominal_akhir, 0, ',', '.') }}
                                        </strong>

                                        @if ($isLunas)
                                            <span class="status lunas">
                                                <i class="fa-solid fa-circle-check"></i>
                                                Lunas
                                            </span>
                                        @elseif ($isPending)
                                            <span class="status pending">
                                                <i class="fa-solid fa-clock"></i>
                                                Pending
                                            </span>
                                        @elseif ($detail->potongan_promo > 0)
                                            <span class="status lunas">
                                                <i class="fa-solid fa-tag"></i>
                                                Potongan Rp {{ number_format($detail->potongan_promo, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        @endforeach
                    </div>

                    <div class="total-box">
                        <div>
                            <span>Total yang harus dibayarkan</span>
                            <p>Jumlah otomatis dihitung berdasarkan tagihan yang dipilih</p>
                        </div>

                        <div>
                            <p>
                                Subtotal:
                                <strong id="subtotalBayar">Rp 0</strong>
                            </p>

                            <p>
                                Potongan Promo:
                                <strong id="potonganBayar">Rp 0</strong>
                            </p>

                            <h3 id="totalBayar">Rp 0</h3>
                        </div>
                    </div>
                </div>

                <button class="btn-du" id="payButton" type="submit">
                    <i class="fa-solid fa-credit-card"></i>
                    Bayar Sekarang
                </button>
            </form>
        </div>
    </section>

    @push('script')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const checks = document.querySelectorAll(".bill-check:not(:disabled)");
                const totalEl = document.getElementById("totalBayar");
                const subtotalEl = document.getElementById("subtotalBayar");
                const potonganEl = document.getElementById("potonganBayar");
                const form = document.getElementById("formDaftarUlang");

                function updateTotal() {
                    let subtotal = 0;
                    let potongan = 0;
                    let total = 0;

                    checks.forEach(check => {
                        const item = check.closest(".bill-item");

                        if (check.checked) {
                            subtotal += parseInt(check.dataset.original || 0);
                            potongan += parseInt(check.dataset.discount || 0);
                            total += parseInt(check.dataset.price || 0);
                            item.classList.add("active");
                        } else {
                            item.classList.remove("active");
                        }
                    });

                    subtotalEl.innerText = "Rp " + subtotal.toLocaleString("id-ID");
                    potonganEl.innerText = "Rp " + potongan.toLocaleString("id-ID");
                    totalEl.innerText = "Rp " + total.toLocaleString("id-ID");
                }

                checks.forEach(check => {
                    check.addEventListener("change", updateTotal);
                });

                form.addEventListener("submit", function(event) {
                    const hasSelected = [...checks].some(check => check.checked);

                    if (!hasSelected) {
                        event.preventDefault();
                        alert("Pilih minimal satu tagihan yang ingin dibayar.");
                    }
                });

                updateTotal();
            });
        </script>
    @endpush

    @include('components.footer')
@endsection
