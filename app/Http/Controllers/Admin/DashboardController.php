<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Guru;
use App\Models\Pendaftaran;
use App\Models\Transaksi;
use App\Models\VirtualTourScene;
use App\Models\WebsiteVisit;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $role = session('admin.role', 'administrasi');
        $role = in_array($role, ['administrasi', 'media'], true)
            ? $role
            : 'administrasi';

        if ($role === 'media') {
            return view('pages.admin.media.dashboard', [
                'showModal' => false,
                'stats' => [
                    'totalBerita' => Berita::count(),
                    'beritaPublish' => Berita::where('status', 'Publish')->count(),
                    'totalGaleri' => Galeri::count(),
                    'galeriPublish' => Galeri::where('status', 'publish')->count(),
                    'totalGuru' => Guru::count(),
                    'totalVirtualTour' => VirtualTourScene::count(),
                ],
                'visitorLabels' => $this->visitorChartByMonth(12)['labels'],
                'visitorData' => $this->visitorChartByMonth(12)['data'],
                'contentLabels' => ['Berita', 'Galeri', 'Guru', 'Virtual Tour'],
                'contentData' => [
                    Berita::count(),
                    Galeri::count(),
                    Guru::count(),
                    VirtualTourScene::count(),
                ],
                'mediaRecentLabels' => $this->mediaContentChartByMonth(6)['labels'],
                'mediaRecentData' => $this->mediaContentChartByMonth(6)['data'],
                'activities' => $this->mediaActivities(),
            ]);
        }

        $stats = [
            'totalBerita' => Berita::count(),
            'totalGaleri' => Galeri::count(),
            'totalGuru' => Guru::count(),
            'totalPendaftar' => Pendaftaran::count(),
            'totalSiswa' => Pendaftaran::where('status', 'diterima')->count(),
            'belumDaftarUlang' => Pendaftaran::where('status', 'diterima')
                ->whereDoesntHave('tagihanSantri', function ($query) {
                    $query->where('status_pembayaran', 'lunas');
                })
                ->count(),
            'totalVirtualTour' => VirtualTourScene::count(),
        ];

        $visitorYear = $this->visitorChartByMonth(12);
        $registrationRecent = $this->registrationChartByMonth(6);
        $sumberInfo = $this->sumberInfoChart();

        $activities = collect()
            ->merge($this->pendaftaranActivities())
            ->merge($this->transaksiActivities())
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('pages.admin.administrasi.dashboard', [
            'showModal' => false,
            'stats' => $stats,
            'visitorLabels' => $visitorYear['labels'],
            'visitorData' => $visitorYear['data'],
            'registrationRecentLabels' => $registrationRecent['labels'],
            'registrationRecentData' => $registrationRecent['data'],
            'sumberInfoLabels' => array_keys($sumberInfo),
            'sumberInfoData' => array_values($sumberInfo),
            'activities' => $activities,
        ]);
    }

    private function registrationChartByMonth(int $monthCount): array
    {
        $start = now()->startOfMonth()->subMonths($monthCount - 1);

        $rows = Pendaftaran::selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('created_at', '>=', $start)
            ->groupBy('tahun', 'bulan')
            ->get()
            ->keyBy(fn ($row) => $row->tahun . '-' . $row->bulan);

        $labels = [];
        $data = [];

        for ($i = 0; $i < $monthCount; $i++) {
            $month = (clone $start)->addMonths($i);
            $key = $month->year . '-' . $month->month;

            $labels[] = $month->translatedFormat('M');
            $data[] = (int) ($rows[$key]->total ?? 0);
        }

        return compact('labels', 'data');
    }

    private function visitorChartByMonth(int $monthCount): array
    {
        $start = now()->startOfMonth()->subMonths($monthCount - 1);

        $rows = WebsiteVisit::selectRaw('YEAR(visited_at) as tahun, MONTH(visited_at) as bulan, COUNT(*) as total')
            ->where('visited_at', '>=', $start->toDateString())
            ->groupBy('tahun', 'bulan')
            ->get()
            ->keyBy(fn ($row) => $row->tahun . '-' . $row->bulan);

        $labels = [];
        $data = [];

        for ($i = 0; $i < $monthCount; $i++) {
            $month = (clone $start)->addMonths($i);
            $key = $month->year . '-' . $month->month;

            $labels[] = $month->translatedFormat('M');
            $data[] = (int) ($rows[$key]->total ?? 0);
        }

        return compact('labels', 'data');
    }

    private function mediaContentChartByMonth(int $monthCount): array
    {
        $start = now()->startOfMonth()->subMonths($monthCount - 1);

        $beritaRows = Berita::selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('created_at', '>=', $start)
            ->groupBy('tahun', 'bulan')
            ->get()
            ->keyBy(fn ($row) => $row->tahun . '-' . $row->bulan);

        $galeriRows = Galeri::selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('created_at', '>=', $start)
            ->groupBy('tahun', 'bulan')
            ->get()
            ->keyBy(fn ($row) => $row->tahun . '-' . $row->bulan);

        $labels = [];
        $data = [];

        for ($i = 0; $i < $monthCount; $i++) {
            $month = (clone $start)->addMonths($i);
            $key = $month->year . '-' . $month->month;

            $labels[] = $month->translatedFormat('M');
            $data[] = (int) (($beritaRows[$key]->total ?? 0) + ($galeriRows[$key]->total ?? 0));
        }

        return compact('labels', 'data');
    }

    private function sumberInfoChart(): array
    {
        $summary = [
            'Media Sosial' => 0,
            'Alumni' => 0,
            'Wali Santri' => 0,
            'Lain-lain' => 0,
        ];

        Pendaftaran::query()
            ->get(['sumber_info'])
            ->each(function (Pendaftaran $pendaftaran) use (&$summary) {
                $items = $pendaftaran->sumber_info ?? [];

                if (is_string($items)) {
                    $items = [$items];
                }

                foreach ($items as $item) {
                    if (array_key_exists($item, $summary)) {
                        $summary[$item]++;
                    }
                }
            });

        return $summary;
    }

    private function pendaftaranActivities()
    {
        return Pendaftaran::latest()
            ->take(5)
            ->get()
            ->map(fn (Pendaftaran $pendaftaran) => [
                'title' => 'Pendaftaran baru masuk',
                'description' => $pendaftaran->nama_lengkap,
                'time' => $pendaftaran->created_at?->diffForHumans(),
                'created_at' => $pendaftaran->created_at,
                'color' => 'blue',
            ]);
    }

    private function transaksiActivities()
    {
        return Transaksi::with('pendaftaran')
            ->whereIn('status', ['settlement'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Transaksi $transaksi) => [
                'title' => 'Pembayaran berhasil',
                'description' => $transaksi->pendaftaran?->nama_lengkap ?? $transaksi->kode_transaksi,
                'time' => ($transaksi->tanggal_bayar ?? $transaksi->updated_at ?? Carbon::now())->diffForHumans(),
                'created_at' => $transaksi->tanggal_bayar ?? $transaksi->updated_at,
                'color' => 'green',
            ]);
    }

    private function mediaActivities()
    {
        $beritas = Berita::latest()
            ->take(5)
            ->get()
            ->map(fn (Berita $berita) => [
                'title' => 'Berita diperbarui',
                'description' => $berita->judul,
                'time' => $berita->updated_at?->diffForHumans(),
                'created_at' => $berita->updated_at,
                'color' => 'blue',
            ]);

        $galeris = Galeri::latest()
            ->take(5)
            ->get()
            ->map(fn (Galeri $galeri) => [
                'title' => 'Galeri diperbarui',
                'description' => $galeri->judul,
                'time' => $galeri->updated_at?->diffForHumans(),
                'created_at' => $galeri->updated_at,
                'color' => 'green',
            ]);

        $gurus = Guru::latest()
            ->take(5)
            ->get()
            ->map(fn (Guru $guru) => [
                'title' => 'Data guru diperbarui',
                'description' => $guru->nama_lengkap,
                'time' => $guru->updated_at?->diffForHumans(),
                'created_at' => $guru->updated_at,
                'color' => 'purple',
            ]);

        return collect()
            ->merge($beritas)
            ->merge($galeris)
            ->merge($gurus)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();
    }
}
