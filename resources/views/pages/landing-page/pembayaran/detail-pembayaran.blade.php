@extends('layout.app')

@include('components.header')

@section('content')
    @php
        $isBankTransfer = $transaksi->payment_type === 'bank_transfer';
        $isQris = $transaksi->payment_type === 'qris';
        $isGopay = $transaksi->payment_type === 'gopay';
        $methodLabel = $isBankTransfer
            ? strtoupper((string) $transaksi->bank) . ' Virtual Account'
            : ($isQris ? 'QRIS' : ($isGopay ? 'GoPay' : 'Metode pembayaran'));
        $paymentNumber = $transaksi->va_number ?? data_get($transaksi->response_midtrans, 'bill_key');
        $paymentNumberLabel = $transaksi->va_number ? 'Nomor Virtual Account' : 'Bill Key';
        $billerCode = data_get($transaksi->response_midtrans, 'biller_code');
        $paymentActionUrl = collect(data_get($transaksi->response_midtrans, 'actions', []))
            ->firstWhere('name', 'deeplink-redirect')['url'] ?? null;
    @endphp

    <section class="payment-detail-page">
        <div class="container">
            <div class="payment-header">
                <div class="payment-status pending">
                    <i class="fa-solid fa-clock"></i>
                    <span id="statusText">{{ ucfirst($transaksi->status) }}</span>
                </div>
                <h2>Detail Pembayaran</h2>
                <p>Silakan selesaikan pembayaran sebelum batas waktu berakhir.</p>
            </div>

            <div class="payment-wrapper">
                <div class="payment-left">
                    <div class="payment-card countdown-card">
                        <h5>Batas Waktu Pembayaran</h5>
                        <div id="countdown">-- : -- : --</div>
                        <p>Pembayaran akan otomatis dibatalkan jika melewati batas waktu.</p>
                    </div>

                    <div class="payment-card">
                        <div class="card-title"><h4>Status Pembayaran</h4></div>
                        <div class="payment-info-list">
                            <div class="info-item"><span>Kode Transaksi</span><strong>{{ $transaksi->kode_transaksi }}</strong></div>
                            <div class="info-item"><span>Metode</span><strong>{{ $methodLabel }}</strong></div>
                            <div class="info-item"><span>Status</span><strong id="statusValue">{{ ucfirst($transaksi->status) }}</strong></div>
                            <div class="info-item"><span>Tanggal</span><strong>{{ $transaksi->created_at->format('d M Y H:i') }}</strong></div>
                        </div>
                    </div>
                </div>

                <div class="payment-right">
                    <div class="payment-card payment-instruction-card">
                        <div class="card-title"><h4>Instruksi Pembayaran</h4></div>

                        @if ($isBankTransfer && $paymentNumber)
                            <div class="payment-method-display">
                                <span class="method-badge">{{ $methodLabel }}</span>
                                <div class="payment-va-box">
                                    <div><small>{{ $paymentNumberLabel }}</small><h3 id="vaNumber">{{ $paymentNumber }}</h3></div>
                                    <button type="button" class="copy-va-btn" data-copy="#vaNumber"><i class="fa-solid fa-copy"></i> Salin</button>
                                </div>
                                @if ($billerCode)
                                    <p>Kode perusahaan: <strong>{{ $billerCode }}</strong></p>
                                @endif
                                <p>Transfer sesuai nominal tagihan melalui ATM, mobile banking, atau internet banking.</p>
                            </div>
                        @elseif (($isQris || $isGopay) && $transaksi->qr_url)
                            <div class="payment-method-display">
                                <span class="method-badge">{{ $methodLabel }}</span>
                                <div class="payment-qr-box"><img src="{{ $transaksi->qr_url }}" alt="Kode QR pembayaran"></div>
                                <p>Scan kode QR di atas dengan aplikasi pembayaran pilihan Anda.</p>
                            </div>
                        @elseif ($isGopay && $paymentActionUrl)
                            <div class="payment-method-display">
                                <span class="method-badge">GoPay</span>
                                <p>Selesaikan pembayaran melalui aplikasi GoPay.</p>
                                <a class="payment-action-btn" href="{{ $paymentActionUrl }}">Buka GoPay</a>
                            </div>
                        @else
                            <div class="payment-empty-state"><i class="fa-solid fa-circle-exclamation"></i><p>Instruksi pembayaran belum tersedia. Kembali dan pilih metode pembayaran.</p></div>
                        @endif
                    </div>

                    <div class="payment-card">
                        <div class="card-title"><h4>Detail Tagihan</h4></div>
                        @forelse ($transaksi->details as $detail)
                            <div class="bill-item"><span>{{ $detail->tagihanSantriDetail?->nama_pembayaran ?? 'Daftar Ulang' }}</span><strong>Rp {{ number_format($detail->nominal, 0, ',', '.') }}</strong></div>
                        @empty
                            <div class="bill-item"><span>{{ $transaksi->pembayaran?->nama_pembayaran ?? 'Tagihan' }}</span><strong>Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</strong></div>
                        @endforelse
                        <div class="bill-item total"><span>Total Pembayaran</span><strong>Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('script')
        @if (session('open_snap') && $transaksi->snap_token)
            <script
                src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
                data-client-key="{{ config('midtrans.client_key') }}">
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    window.snap.pay(@json($transaksi->snap_token), {
                        onSuccess: () => window.location.reload(),
                        onPending: () => window.location.reload(),
                        onError: () => window.location.reload(),
                        onClose: () => window.location.reload(),
                    });
                });
            </script>
        @endif

        <script>
            const expiredAt = @json($transaksi->expired_at?->toIso8601String());
            const statusUrl = @json(url('/api/payment-status/' . $transaksi->id));
            const countdown = document.getElementById('countdown');

            function renderCountdown() {
                if (!expiredAt || !countdown) return;
                const seconds = Math.max(0, Math.floor((new Date(expiredAt).getTime() - Date.now()) / 1000));
                countdown.textContent = `${String(Math.floor(seconds / 3600)).padStart(2, '0')} : ${String(Math.floor(seconds % 3600 / 60)).padStart(2, '0')} : ${String(seconds % 60).padStart(2, '0')}`;
            }

            async function checkPaymentStatus() {
                try {
                    const response = await fetch(statusUrl, { headers: { Accept: 'application/json' } });
                    if (!response.ok) return;
                    const data = await response.json();
                    document.getElementById('statusText').textContent = data.status;
                    document.getElementById('statusValue').textContent = data.status;
                    if (data.redirect_url) window.location.href = data.redirect_url;
                } catch (error) { console.log(error); }
            }

            document.querySelectorAll('[data-copy]').forEach(button => button.addEventListener('click', async () => {
                const text = document.querySelector(button.dataset.copy)?.textContent.trim();
                if (text) await navigator.clipboard.writeText(text);
            }));
            renderCountdown();
            setInterval(renderCountdown, 1000);
            checkPaymentStatus();
            setInterval(checkPaymentStatus, 5000);
        </script>
    @endpush

    @include('components.footer')
@endsection
