@extends('layout.app')

@include('components.header')

@section('content')

<section class="payment-success-page">

    <div class="container">

        <div class="success-wrapper">

            <!-- CARD -->
            <div class="success-card">

                @php

                    $status = $transaksi->status;

                    $isSuccess = $status == 'settlement';

                    $isPending = $status == 'pending';

                    $isFailed = in_array($status, [
                        'expire',
                        'cancel',
                        'deny'
                    ]);

                @endphp

                <!-- ICON -->
                <div class="success-icon">

                    <div class="icon-circle

                        {{ $isSuccess ? 'success' : '' }}
                        {{ $isPending ? 'pending' : '' }}
                        {{ $isFailed ? 'failed' : '' }}

                    ">

                        @if ($isSuccess)

                            <i class="fa-solid fa-check"></i>

                        @elseif ($isPending)

                            <i class="fa-solid fa-clock"></i>

                        @else

                            <i class="fa-solid fa-xmark"></i>

                        @endif

                    </div>

                </div>

                <!-- STATUS -->
                <div class="success-status">

                    @if ($isSuccess)

                        <span class="status-badge success">
                            Pembayaran Berhasil
                        </span>

                        <h2>
                            Pembayaran Telah Diterima
                        </h2>

                        <p>
                            Terima kasih, pembayaran pendaftaran berhasil diproses.
                        </p>

                    @elseif ($isPending)

                        <span class="status-badge pending">
                            Menunggu Pembayaran
                        </span>

                        <h2>
                            Pembayaran Pending
                        </h2>

                        <p>
                            Silakan selesaikan pembayaran sebelum batas waktu berakhir.
                        </p>

                    @else

                        <span class="status-badge failed">
                            Pembayaran Gagal
                        </span>

                        <h2>
                            Pembayaran Tidak Berhasil
                        </h2>

                        <p>
                            Pembayaran gagal atau dibatalkan.
                        </p>

                    @endif

                </div>

                <!-- PAYMENT INFO -->
                <div class="payment-info-grid">

                    <div class="info-item">

                        <span>Nama Santri</span>

                        <strong>
                            {{ $transaksi->pendaftaran->nama_lengkap }}
                        </strong>

                    </div>

                    <div class="info-item">

                        <span>Jenjang</span>

                        <strong>
                            {{ $transaksi->pembayaran->jenjang }}
                        </strong>

                    </div>

                    <div class="info-item">

                        <span>Pembayaran</span>

                        <strong>
                            @if ($transaksi->details->isNotEmpty())
                                Daftar Ulang ({{ $transaksi->details->count() }} item)
                            @else
                                {{ $transaksi->pembayaran->nama_pembayaran }}
                            @endif
                        </strong>

                    </div>

                    <div class="info-item">

                        <span>Tanggal</span>

                        <strong>
                            {{ $transaksi->created_at->format('d M Y') }}
                        </strong>

                    </div>

                    <div class="info-item">

                        <span>Metode Pembayaran</span>

                        <strong>

                            {{ $transaksi->payment_type ?? '-' }}

                            {{ $transaksi->bank ?? '' }}

                        </strong>

                    </div>

                    <div class="info-item">

                        <span>Status</span>

                        <strong class="
                            {{ $isSuccess ? 'text-success' : '' }}
                            {{ $isPending ? 'text-warning' : '' }}
                            {{ $isFailed ? 'text-danger' : '' }}
                        ">

                            {{ ucfirst($transaksi->status) }}

                        </strong>

                    </div>

                </div>

                <!-- TOTAL -->
                <div class="payment-total-box">

                    <span class="payment-title">
                        Total Pembayaran
                    </span>

                    @if ($transaksi->details->isNotEmpty())
                        <div class="payment-list">
                            @foreach ($transaksi->details as $detail)
                                <div class="payment-item">
                                    <span class="payment-name">
                                        {{ $detail->tagihanSantriDetail?->nama_pembayaran ?? 'Daftar Ulang' }}
                                    </span>

                                    <span class="payment-price">
                                        Rp {{ number_format($detail->nominal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="payment-total">
                        <span>Total yang harus dibayar</span>

                        <h3>
                            Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                        </h3>
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="success-action">

                    @if ($isSuccess)

                        <a href="{{ route('download-bukti', $transaksi) }}"
                           class="btn-download">

                            <i class="fa-solid fa-download"></i>

                            Download Bukti

                        </a>

                    @endif

                    @if ($isFailed)

                        <a
                            href="{{ $transaksi->details->isNotEmpty() ? route('pembayaran-daftar-ulang', $transaksi) : route('pembayaran-pendaftaran', $transaksi) }}"
                            class="btn-print">

                            <i class="fa-solid fa-rotate-right"></i>

                            Coba Lagi

                        </a>

                    @endif

                </div>

            </div>

        </div>

    </div>

</section>

@include('components.footer')

@endsection
