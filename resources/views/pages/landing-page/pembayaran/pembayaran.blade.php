@extends('layout.app')

@include('components.header')

@section('content')
    <section class="pembayaran-page">

        <div class="container">

            <!-- HEADER -->
            <div class="page-header">

                <h2>
                    <i class="fa-solid fa-wallet"></i>
                    {{ $title }}
                </h2>

                <p>
                    {{ $subtitle }}
                </p>

            </div>

            <div class="payment-wrapper">

                <!-- LEFT -->
                <div class="payment-detail">

                    <div class="payment-card">

                        <div class="card-header">
                            <h4>Detail Pembayaran</h4>
                        </div>

                        <div class="payment-item">
                            <span>Biaya Pendaftaran</span>

                            <strong>
                                Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="payment-item">
                            <span>Status</span>

                            <strong style="text-transform: capitalize">
                                {{ $transaksi->status }}
                            </strong>
                        </div>

                        <div class="payment-item total">

                            <span>Total Pembayaran</span>

                            <strong>
                                Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                            </strong>

                        </div>

                    </div>

                    <!-- DATA SANTRI -->
                    <div class="payment-card">

                        <div class="card-title">
                            <h4>Data Santri</h4>
                        </div>

                        <div class="payment-info-list">

                            <div class="info-item">
                                <span>Nama Lengkap</span>

                                <strong>
                                    {{ $transaksi->pendaftaran->nama_lengkap }}
                                </strong>
                            </div>

                            <div class="info-item">
                                <span>Jenis Kelamin</span>

                                <strong>
                                    {{ $transaksi->pendaftaran->jenis_kelamin }}
                                </strong>
                            </div>

                            <div class="info-item">
                                <span>NISN</span>

                                <strong>
                                    {{ $transaksi->pendaftaran->pendidikan?->nisn ?? '-' }}
                                </strong>
                            </div>

                        </div>

                    </div>


                </div>

                <!-- RIGHT -->
                <div class="payment-method">

                    <div class="payment-card">

                        <div class="card-header">
                            <h4>Pilih Metode Pembayaran</h4>
                        </div>
                        <form id="paymentMethodForm" action="{{ route('pilih-metode', ['transaksi' => $transaksi->id]) }}" method="POST">

                            @csrf

                            <div class="method-list">

                                <!-- ================= BANK ================= -->
                                <div class="method-item">

                                    <div class="method-content method-trigger">

                                        <div class="method-left">

                                            <i class="fa-solid fa-building-columns"></i>

                                            <div>
                                                <h5>Transfer Bank</h5>
                                                <p>BCA, BRI, BNI, Mandiri</p>
                                            </div>

                                        </div>

                                        <div class="method-right">

                                            <span class="badge-method">
                                                Virtual Account
                                            </span>

                                        </div>

                                    </div>

                                    <!-- DROPDOWN -->
                                    <div class="method-dropdown">

                                        <label class="sub-method">
                                            <input type="radio" name="payment_method" value="bca">
                                            <span>BCA Virtual Account</span>
                                        </label>

                                        <label class="sub-method">
                                            <input type="radio" name="payment_method" value="bri">
                                            <span>BRI Virtual Account</span>
                                        </label>

                                        <label class="sub-method">
                                            <input type="radio" name="payment_method" value="bni">
                                            <span>BNI Virtual Account</span>
                                        </label>

                                        <label class="sub-method">
                                            <input type="radio" name="payment_method" value="mandiri">
                                            <span>Mandiri Virtual Account</span>
                                        </label>

                                    </div>

                                </div>

                                <!-- ================= EWALLET ================= -->
                                <div class="method-item">

                                    <div class="method-content method-trigger">

                                        <div class="method-left">

                                            <i class="fa-solid fa-wallet"></i>

                                            <div>
                                                <h5>E-Wallet</h5>
                                                <p>GoPay, DANA, OVO, ShopeePay</p>
                                            </div>

                                        </div>

                                        <div class="method-right">

                                            <span class="badge-method">
                                                Instant
                                            </span>
                                        </div>

                                    </div>

                                    <!-- DROPDOWN -->
                                    <div class="method-dropdown">

                                        <label class="sub-method">
                                            <input type="radio" name="payment_method" value="gopay">
                                            <span>GoPay</span>
                                        </label>

                                        <label class="sub-method">
                                            <input type="radio" name="payment_method" value="dana">
                                            <span>DANA</span>
                                        </label>

                                        <label class="sub-method">
                                            <input type="radio" name="payment_method" value="ovo">
                                            <span>OVO</span>
                                        </label>

                                        <label class="sub-method">
                                            <input type="radio" name="payment_method" value="shopeepay">
                                            <span>ShopeePay</span>
                                        </label>

                                    </div>

                                </div>

                                <!-- ================= QRIS ================= -->
                                <div class="method-item qris-item">

                                    <div class="method-content method-trigger">

                                        <div class="method-left">

                                            <i class="fa-solid fa-qrcode"></i>

                                            <div>
                                                <h5>QRIS</h5>
                                                <p>Scan QR menggunakan aplikasi pembayaran</p>
                                            </div>

                                        </div>

                                        <div class="method-right">

                                            <span class="badge-method">
                                                QR Payment
                                            </span>
                                        </div>

                                    </div>
                                    <div class="method-dropdown">

                                        <label class="sub-method">

                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="qris"
                                            >

                                            <span>QRIS</span>

                                        </label>

                                    </div>

                                </div>

                            </div>

                            <!-- BUTTON -->
                            <button id="payNowButton" type="submit" class="btn-payment">

                                <i class="fa-solid fa-lock"></i>
                                Bayar Sekarang

                            </button>

                            <button id="continuePaymentButton" type="button" class="btn-payment"
                                @if (! ($transaksi->status === 'pending' && $transaksi->snap_token && (! $transaksi->expired_at || $transaksi->expired_at->isFuture()))) hidden @endif>
                                <i class="fa-solid fa-lock"></i>
                                Lanjutkan Pembayaran
                            </button>

                            <p id="snapPaymentMessage" class="mt-3 mb-0" role="alert" hidden></p>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </section>

    @push('script')
        <script
            src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('midtrans.client_key') }}">
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const paymentForm = document.getElementById('paymentMethodForm');
                const payNowButton = document.getElementById('payNowButton');
                const continuePaymentButton = document.getElementById('continuePaymentButton');
                const paymentMessage = document.getElementById('snapPaymentMessage');
                const successUrl = @json(route('pembayaran-berhasil', $transaksi));
                const paymentStatusUrl = @json(url('/api/payment-status/' . $transaksi->id));
                const paymentConfirmUrl = @json(url('/api/payment-confirm/' . $transaksi->id));
                let snapToken = @json($transaksi->status === 'pending' ? $transaksi->snap_token : null);

                function showMessage(message, type = 'danger') {
                    paymentMessage.textContent = message;
                    paymentMessage.className = `mt-3 mb-0 text-${type}`;
                    paymentMessage.hidden = false;
                }

                function openSnap() {
                    if (!snapToken || !window.snap) {
                        showMessage('Pembayaran belum dapat dibuka. Silakan coba lagi.');
                        return;
                    }

                    window.snap.pay(snapToken, {
                        onSuccess: () => {
                            window.snap.hide();
                            confirmPayment();
                        },
                        onPending: () => {
                            continuePaymentButton.hidden = false;
                            showMessage('Pembayaran masih menunggu penyelesaian. Anda dapat melanjutkannya kapan saja.', 'warning');
                        },
                        onError: () => {
                            continuePaymentButton.hidden = false;
                            showMessage('Pembayaran gagal diproses. Silakan coba lagi atau hubungi administrator.');
                        },
                        onClose: () => {
                            continuePaymentButton.hidden = false;
                            showMessage('Pembayaran belum diselesaikan. Klik "Lanjutkan Pembayaran" untuk melanjutkan transaksi yang sama.', 'warning');
                        },
                    });
                }

                paymentForm.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    const selectedMethod = paymentForm.querySelector('input[name="payment_method"]:checked');

                    if (!selectedMethod) {
                        showMessage('Silakan pilih metode pembayaran terlebih dahulu.', 'warning');
                        return;
                    }

                    payNowButton.disabled = true;
                    paymentMessage.hidden = true;

                    try {
                        const response = await fetch(paymentForm.action, {
                            method: 'POST',
                            headers: {
                                Accept: 'application/json',
                                'X-CSRF-TOKEN': paymentForm.querySelector('[name="_token"]').value,
                            },
                            body: new FormData(paymentForm),
                        });
                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Instruksi pembayaran belum dapat dibuat.');
                        }

                        snapToken = data.snap_token;
                        continuePaymentButton.hidden = false;
                        openSnap();
                    } catch (error) {
                        showMessage(error.message || 'Instruksi pembayaran belum dapat dibuat.');
                    } finally {
                        payNowButton.disabled = false;
                    }
                });

                continuePaymentButton.addEventListener('click', openSnap);

                async function confirmPayment() {
                    for (let attempt = 0; attempt < 5; attempt++) {
                        try {
                            const response = await fetch(paymentConfirmUrl, {
                                method: 'POST',
                                headers: { Accept: 'application/json' },
                            });
                            const data = await response.json();

                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                                return;
                            }
                        } catch (error) {
                            console.log(error);
                        }

                        await new Promise(resolve => setTimeout(resolve, 1000));
                    }

                    showMessage('Pembayaran sedang dikonfirmasi. Halaman akan berubah otomatis setelah Midtrans mengonfirmasi pembayaran.', 'warning');
                    checkPaymentStatus();
                }

                async function checkPaymentStatus() {
                    try {
                        const response = await fetch(paymentStatusUrl, { headers: { Accept: 'application/json' } });
                        if (!response.ok) return;
                        const data = await response.json();
                        if (data.redirect_url) window.location.href = data.redirect_url;
                    } catch (error) {
                        console.log(error);
                    }
                }

                checkPaymentStatus();
                setInterval(checkPaymentStatus, 5000);

                const methodItems = document.querySelectorAll(".method-item");

                methodItems.forEach(item => {

                    const trigger = item.querySelector(".method-trigger");

                    trigger.addEventListener("click", function() {

                        // tutup semua selain yang dipilih
                        methodItems.forEach(other => {

                            if (other !== item) {
                                other.classList.remove("active");
                            }

                        });

                        // toggle active
                        item.classList.toggle("active");

                    });

                });

            });
        </script>
    @endpush

    @include('components.footer')
@endsection
