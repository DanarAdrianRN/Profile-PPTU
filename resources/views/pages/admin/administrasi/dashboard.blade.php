@extends('layout.app')

@section('content')
    <div class="admin-layout">
        @include('components.sidebar-admin')
        <div class="admin-main">
            @include('components.header-admin', ['title' => 'Dashboard'])
            <section class="dashboard-admin">
                <!-- STAT -->
                <div class="stats-grid">
                    <div class="stats-card">
                        <div class="stats-icon blue">
                            <i class="fa-solid fa-newspaper"></i>
                        </div>
                        <div class="stats-info">
                            <span>Total Berita</span>
                            <h3>{{ number_format($stats['totalBerita']) }}</h3>
                        </div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-icon green">
                            <i class="fa-solid fa-images"></i>
                        </div>
                        <div class="stats-info">
                            <span>Total Galeri</span>
                            <h3>{{ number_format($stats['totalGaleri']) }}</h3>
                        </div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-icon orange">
                            <i class="fa-solid fa-user-group"></i>
                        </div>
                        <div class="stats-info">
                            <span>Guru & Ustadz</span>
                            <h3>{{ number_format($stats['totalGuru']) }}</h3>
                        </div>
                    </div>
                    <div class="stats-card">
                        <div class="stats-icon red">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                        <div class="stats-info">
                            <span>Pendaftar Baru</span>
                            <h3>{{ number_format($stats['totalPendaftar']) }}</h3>
                        </div>
                    </div>
                    {{-- TOTAL SISWA --}}
                    <div class="stats-card">
                        <div class="stats-icon purple">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div class="stats-info">
                            <span>Santri Diterima</span>
                            <h3>{{ number_format($stats['totalSiswa']) }}</h3>
                        </div>
                    </div>
                    {{-- BELUM DAFTAR ULANG --}}
                    <div class="stats-card">
                        <div class="stats-icon yellow">
                            <i class="fa-solid fa-user-clock"></i>
                        </div>
                        <div class="stats-info">
                            <span>Belum Daftar Ulang</span>
                            <h3>{{ number_format($stats['belumDaftarUlang']) }}</h3>
                        </div>
                    </div>
                    {{-- VIRTUAL TOUR --}}
                    <div class="stats-card">
                        <div class="stats-icon blue">
                            <i class="fa-solid fa-vr-cardboard"></i>
                        </div>
                        <div class="stats-info">
                            <span>Virtual Tour</span>
                            <h3>{{ number_format($stats['totalVirtualTour']) }} Spot</h3>
                        </div>
                    </div>
                </div>
                <!-- CHART GRID -->
                <div class="dashboard-chart-grid">
                    <!-- LINE CHART -->
                    <div class="chart-card large">
                        <div class="card-head">
                            <h3>Pengunjung Website</h3>
                        </div>
                        <div id="pengunjungChart"></div>
                    </div>
                    <!-- DONUT -->
                    <div class="chart-card">
                        <div class="card-head">
                            <h3>Informasi Masuk Pesantren</h3>
                        </div>
                        <div id="donutChart"></div>
                    </div>
                </div>
                <!-- SECOND GRID -->
                <div class="dashboard-chart-grid second">
                    <!-- BAR -->
                    <div class="chart-card">
                        <div class="card-head">
                            <h3>Registrasi Bulanan</h3>
                        </div>
                        <div id="paymentChart"></div>
                    </div>
                    <!-- ACTIVITY -->
                    <div class="chart-card activity-card">
                        <div class="card-head">
                            <h3>Aktivitas Terbaru</h3>
                        </div>
                        <div class="activity-list">
                            @forelse ($activities as $activity)
                                <div class="activity-item">
                                    <div class="dot {{ $activity['color'] }}"></div>
                                    <div class="text">
                                        <h4>{{ $activity['title'] }}</h4>
                                        <span>{{ $activity['description'] }} - {{ $activity['time'] }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="activity-item">
                                    <div class="dot"></div>
                                    <div class="text">
                                        <h4>Belum ada aktivitas</h4>
                                        <span>Aktivitas terbaru akan tampil di sini</span>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('script')
    <script>
        // Ensure ApexCharts is loaded (and containers exist)
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.ApexCharts) {
                console.error('ApexCharts not loaded');
                return;
            }

            const $pengunjung = document.querySelector('#pengunjungChart');
            const $donut = document.querySelector('#donutChart');
            const $payment = document.querySelector('#paymentChart');

            if (!$pengunjung || !$donut || !$payment) {
                console.error('Chart container missing');
                return;
            }



            // LINE CHART
            const pengunjungChart = new ApexCharts(
                document.querySelector("#pengunjungChart"), {

                    chart: {
                        type: 'area',
                        height: 350,
                        toolbar: {
                            show: false
                        }
                    },

                    series: [{
                        name: 'Pengunjung',
                        data: @json($visitorData)
                    }],

                    colors: ['#0f7a8a'],

                    stroke: {
                        curve: 'smooth',
                        width: 4
                    },

                    fill: {
                        opacity: 0.2
                    },

                    dataLabels: {
                        enabled: false
                    },

                    xaxis: {
                        categories: @json($visitorLabels)
                    }

                });

            pengunjungChart.render();



            // DONUT
            const donutChart = new ApexCharts(
                document.querySelector("#donutChart"), {

                    chart: {
                        type: 'donut',
                        height: 320
                    },

                    labels: @json($sumberInfoLabels),

                    series: @json($sumberInfoData),

                    colors: ['#0f7a8a', '#ffd500', '#a855f7', '#ef4444'],

                    legend: {
                        position: 'bottom'
                    }

                });

            donutChart.render();



            // BAR
            const paymentChart = new ApexCharts(
                document.querySelector("#paymentChart"), {

                    chart: {
                        type: 'bar',
                        height: 320,
                        toolbar: {
                            show: false
                        }
                    },

                    series: [{
                        name: 'Registrasi',
                        data: @json($registrationRecentData)
                    }],


                    colors: ['#ffd500'],

                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            columnWidth: '50%'
                        }
                    },

                    dataLabels: {
                        enabled: false
                    },

                    xaxis: {
                        categories: @json($registrationRecentLabels)
                    }

                });

            paymentChart.render();


        });
    </script>
@endpush
