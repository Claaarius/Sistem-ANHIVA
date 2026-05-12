<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DashboardKonten;

class DashboardKontenSeeder extends Seeder
{
    public function run(): void
    {
        // Hero Section
        DashboardKonten::create([
            'tipe' => 'hero',
            'judul' => 'ANHIVA',
            'konten' => 'Sistem Edukasi dan Skrining Risiko HIV Berbasis Anonim. Lindungi diri Anda dengan pengetahuan yang tepat dan skrining risiko secara anonim.',
            'tombol_teks' => 'Mulai Skrining',
            'tombol_link' => '/skrining',
            'urutan' => 1,
            'aktif' => true,
        ]);

        // Fakta HIV
        $fakta = [
            [
                'judul' => 'Jumlah ODHIV di Indonesia',
                'konten' => 'Hingga Desember 2023, tercatat lebih dari 519.000 orang terdiagnosis HIV di Indonesia. Jumlah ini terus meningkat setiap tahunnya.',
                'sumber' => 'Kemenkes RI, 2023',
            ],
            [
                'judul' => 'Pengobatan ARV Gratis',
                'konten' => 'Pemerintah Indonesia menyediakan obat Antiretroviral (ARV) secara gratis di seluruh fasilitas kesehatan pemerintah bagi semua ODHIV.',
                'sumber' => 'Kemenkes RI',
            ],
            [
                'judul' => 'Deteksi Dini Menyelamatkan',
                'konten' => 'Dengan terapi ARV yang dimulai sejak dini, harapan hidup ODHIV hampir sama dengan orang yang tidak terinfeksi HIV.',
                'sumber' => 'WHO, 2023',
            ],
            [
                'judul' => 'Target Global UNAIDS',
                'konten' => 'Target 95-95-95: 95% ODHIV mengetahui statusnya, 95% menerima ARV, dan 95% memiliki viral load tersupresi pada tahun 2030.',
                'sumber' => 'UNAIDS',
            ],
        ];

        foreach ($fakta as $i => $f) {
            DashboardKonten::create([
                'tipe' => 'fakta_hiv',
                'judul' => $f['judul'],
                'konten' => $f['konten'],
                'sumber' => $f['sumber'],
                'urutan' => $i + 1,
                'aktif' => true,
            ]);
        }
    }
}
