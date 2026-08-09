<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\Periode;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Guru;
use App\Models\Pendaftaran;
use App\Models\PendaftaranHasilTes;
use App\Models\Transaksi;
use App\Models\VirtualTourScene;
use App\Models\WebsiteVisit;

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
                'activities' => $this->resolveActivities('media'),
            ]);
        }

        $periodeAktif = Periode::where('is_active', true)->first();
        $pemasukanPeriode = $periodeAktif
            ? Transaksi::where('status', 'settlement')
                ->whereHas('pendaftaran', function ($query) use ($periodeAktif) {
                    $query->where('periode_id', $periodeAktif->id);
                })
                ->sum('nominal')
            : 0;

        $stats = [
            'totalSemuaSantri' => Pendaftaran::count(),
            'totalPendaftar' => $periodeAktif
                ? Pendaftaran::where('periode_id', $periodeAktif->id)->count()
                : 0,

            'pendaftaranBelumBayar' => $periodeAktif
                ? Pendaftaran::where('periode_id', $periodeAktif->id)
                    ->where('status', 'belum_bayar')
                    ->count()
                : 0,

            'menungguVerifikasi' => $periodeAktif
                ? Pendaftaran::where('periode_id', $periodeAktif->id)
                    ->where('status', 'menunggu_verifikasi')
                    ->count()
                : 0,
            'totalSiswa' => $periodeAktif
                ? Pendaftaran::where('periode_id', $periodeAktif->id)
                    ->where('status', 'diterima')
                    ->count()
                : 0,
            'belumDaftarUlang' => $periodeAktif
                ? Pendaftaran::where('periode_id', $periodeAktif->id)
                    ->where('status', 'diterima')
                    ->whereDoesntHave('tagihanSantri', function ($query) {
                        $query->where('status_pembayaran', 'lunas');
                    })
                    ->count()
                : 0,
            'sudahTes' => $periodeAktif
                ? PendaftaranHasilTes::whereHas('pendaftaran', function ($query) use ($periodeAktif) {
                    $query->where('periode_id', $periodeAktif->id);
                })->count()
                : 0,
            'pemasukanPeriode' => $pemasukanPeriode,
            'pemasukanPeriodeFormatted' => $this->formatNominalDashboard($pemasukanPeriode),
        ];
 
        $visitorYear = $this->visitorChartByMonth(12);
        $registrationRecent = $this->registrationChartByMonth(6);
        $sumberInfo = $this->sumberInfoChart();
 
        $activities = $this->resolveActivities('administrasi');
 
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

    private function formatNominalDashboard(float|int $nominal): string
    {
        $format = function ($value) {
            $hasil = number_format($value, 1, ',', '.');

            return str_ends_with($hasil, ',0')
                ? substr($hasil, 0, -2)
                : $hasil;
        };

        if ($nominal >= 1000000000) {
            return $format($nominal / 1000000000) . ' M';
        }

        if ($nominal >= 1000000) {
            return $format($nominal / 1000000) . ' Jt';
        }

        if ($nominal >= 1000) {
            return $format($nominal / 1000) . ' Rb';
        }

        return number_format($nominal, 0, ',', '.');
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

    /**
     * Ambil feed aktivitas dari AdminActivityLog, difilter sesuai role admin
     * pelakunya — dipakai baik untuk dashboard administrasi maupun media.
     */
private function resolveActivities(string $role)
{
    return AdminActivityLog::with('admin')
        ->where(function ($query) use ($role) {
            $query->whereHas('admin', function ($q) use ($role) {
                $q->where('role', $role);
            })->orWhereNull('admin_id');
        })
        ->latest('created_at')
        ->take(8)
        ->get()
        ->map(fn (AdminActivityLog $log) => [
            'title' => match ($log->aksi) {
                'login' => 'Login',
                'logout' => 'Logout',
                'status_ubah' => 'Ubah Status Pendaftaran',
                'catat_bayar' => 'Catat Pembayaran',
                'create' => 'Tambah Data',
                'update' => 'Perbarui Data',
                'delete' => 'Hapus Data',
                default => ucfirst(str_replace('_', ' ', $log->aksi)),
            },

            'description' => ($log->admin?->username ?? 'Sistem')
                . ' - '
                . $log->deskripsi,

            'time' => $log->created_at->diffForHumans(),

            'created_at' => $log->created_at,

            'color' => match ($log->aksi) {
                'login' => 'blue',
                'logout' => 'gray',
                'status_ubah' => 'purple',
                'catat_bayar' => 'green',
                'delete' => 'red',
                'create' => 'green',
                'update' => 'blue',
                default => 'blue',
            },
        ]);
}
}