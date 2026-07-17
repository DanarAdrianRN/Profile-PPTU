<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Guru;

class GuruController extends Controller
{
    private const KATEGORI_GURU = [
        'Madrasah Diniyah',
        'SMP',
        'SMK',
        "Madrasah Al-Qur'an",
        'TPQ',
    ];

    public function index()
    {
        $gurus = Guru::where('status', 'Aktif')
            ->latest()
            ->get();

        $totalPengajar = Guru::count();

        $guruSMP = Guru::where('kategori', 'SMP')->count();

        $guruSMK = Guru::where('kategori', 'SMK')->count();

        $ustadzMadin = Guru::where('kategori', 'Madrasah Diniyah')->count();
        $ustadzMadqur = Guru::where('kategori', "Madrasah Al-Qur'an")->count();
        $ustadzTPQ = Guru::where('kategori', 'TPQ')->count();
        $kategoriGuru = self::KATEGORI_GURU;

        return view(
            'pages.landing-page.guru',
            compact(
                'gurus',
                'totalPengajar',
                'guruSMP',
                'guruSMK',
                'ustadzMadin',
                'ustadzMadqur',
                'ustadzTPQ',
                'kategoriGuru'
            )
        );
    }
}
