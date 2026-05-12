<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pertanyaan;

class PertanyaanSeeder extends Seeder
{
    public function run(): void
    {
        $pertanyaan = [
            [
                'isi_pertanyaan' => 'Apakah Anda pernah melakukan hubungan seksual tanpa menggunakan kondom?',
                'pilihan_jawaban' => [
                    ['teks' => 'Tidak pernah', 'bobot' => 0],
                    ['teks' => 'Pernah, 1-2 kali', 'bobot' => 2],
                    ['teks' => 'Pernah, lebih dari 2 kali', 'bobot' => 4],
                    ['teks' => 'Sering / rutin', 'bobot' => 6],
                ],
                'kategori' => 'Perilaku Seksual',
                'urutan' => 1,
            ],
            [
                'isi_pertanyaan' => 'Berapa jumlah pasangan seksual yang Anda miliki dalam 12 bulan terakhir?',
                'pilihan_jawaban' => [
                    ['teks' => 'Tidak ada', 'bobot' => 0],
                    ['teks' => '1 orang (pasangan tetap)', 'bobot' => 1],
                    ['teks' => '2-5 orang', 'bobot' => 4],
                    ['teks' => 'Lebih dari 5 orang', 'bobot' => 6],
                ],
                'kategori' => 'Perilaku Seksual',
                'urutan' => 2,
            ],
            [
                'isi_pertanyaan' => 'Apakah Anda pernah didiagnosis atau mengalami gejala Infeksi Menular Seksual (IMS) seperti sifilis, gonore, klamidia, atau herpes genital?',
                'pilihan_jawaban' => [
                    ['teks' => 'Tidak pernah', 'bobot' => 0],
                    ['teks' => 'Pernah, sudah diobati', 'bobot' => 3],
                    ['teks' => 'Pernah, tidak/belum diobati', 'bobot' => 5],
                    ['teks' => 'Tidak tahu / tidak pernah diperiksa', 'bobot' => 2],
                ],
                'kategori' => 'Riwayat Kesehatan',
                'urutan' => 3,
            ],
            [
                'isi_pertanyaan' => 'Apakah Anda pernah menggunakan narkoba suntik atau berbagi jarum suntik dengan orang lain?',
                'pilihan_jawaban' => [
                    ['teks' => 'Tidak pernah', 'bobot' => 0],
                    ['teks' => 'Pernah, tapi sudah berhenti', 'bobot' => 4],
                    ['teks' => 'Masih aktif menggunakan', 'bobot' => 7],
                ],
                'kategori' => 'Penggunaan Narkoba',
                'urutan' => 4,
            ],
            [
                'isi_pertanyaan' => 'Apakah Anda pernah menerima transfusi darah atau produk darah yang tidak diketahui status skriningnya?',
                'pilihan_jawaban' => [
                    ['teks' => 'Tidak pernah', 'bobot' => 0],
                    ['teks' => 'Pernah, di fasilitas kesehatan resmi', 'bobot' => 1],
                    ['teks' => 'Pernah, di tempat yang tidak jelas/informal', 'bobot' => 5],
                    ['teks' => 'Tidak yakin / tidak ingat', 'bobot' => 2],
                ],
                'kategori' => 'Riwayat Kesehatan',
                'urutan' => 5,
            ],
            [
                'isi_pertanyaan' => 'Apakah Anda mengetahui status HIV dari pasangan seksual Anda saat ini atau sebelumnya?',
                'pilihan_jawaban' => [
                    ['teks' => 'Ya, negatif (sudah dites)', 'bobot' => 0],
                    ['teks' => 'Ya, positif HIV', 'bobot' => 6],
                    ['teks' => 'Tidak tahu / tidak pernah ditanyakan', 'bobot' => 3],
                    ['teks' => 'Tidak memiliki pasangan', 'bobot' => 0],
                ],
                'kategori' => 'Perilaku Seksual',
                'urutan' => 6,
            ],
            [
                'isi_pertanyaan' => 'Apakah Anda pernah menjalani tes HIV sebelumnya?',
                'pilihan_jawaban' => [
                    ['teks' => 'Ya, dalam 6 bulan terakhir (hasil negatif)', 'bobot' => 0],
                    ['teks' => 'Ya, lebih dari 6 bulan lalu (hasil negatif)', 'bobot' => 1],
                    ['teks' => 'Ya, hasilnya positif', 'bobot' => 0],
                    ['teks' => 'Belum pernah', 'bobot' => 2],
                ],
                'kategori' => 'Riwayat Tes',
                'urutan' => 7,
            ],
            [
                'isi_pertanyaan' => 'Apakah Anda pernah mengalami gejala-gejala berikut secara bersamaan dalam waktu lama: demam berkepanjangan, penurunan berat badan drastis, diare kronis, atau pembengkakan kelenjar getah bening?',
                'pilihan_jawaban' => [
                    ['teks' => 'Tidak pernah', 'bobot' => 0],
                    ['teks' => 'Pernah mengalami 1-2 gejala di atas', 'bobot' => 2],
                    ['teks' => 'Pernah mengalami 3 atau lebih gejala di atas', 'bobot' => 5],
                    ['teks' => 'Sedang mengalami saat ini', 'bobot' => 6],
                ],
                'kategori' => 'Gejala Klinis',
                'urutan' => 8,
            ],
            [
                'isi_pertanyaan' => 'Apakah pekerjaan atau aktivitas Anda melibatkan potensi paparan cairan tubuh orang lain (misalnya: tenaga kesehatan, petugas laboratorium)?',
                'pilihan_jawaban' => [
                    ['teks' => 'Tidak', 'bobot' => 0],
                    ['teks' => 'Ya, dengan alat pelindung diri lengkap', 'bobot' => 1],
                    ['teks' => 'Ya, tanpa alat pelindung diri yang memadai', 'bobot' => 4],
                ],
                'kategori' => 'Paparan Risiko',
                'urutan' => 9,
            ],
            [
                'isi_pertanyaan' => 'Apakah Anda pernah melakukan tindikan, tato, atau prosedur medis menggunakan alat yang tidak steril?',
                'pilihan_jawaban' => [
                    ['teks' => 'Tidak pernah', 'bobot' => 0],
                    ['teks' => 'Pernah, di tempat yang terpercaya/steril', 'bobot' => 1],
                    ['teks' => 'Pernah, di tempat yang tidak jelas kebersihannya', 'bobot' => 4],
                    ['teks' => 'Tidak yakin tentang sterilitas alatnya', 'bobot' => 3],
                ],
                'kategori' => 'Paparan Risiko',
                'urutan' => 10,
            ],
        ];

        foreach ($pertanyaan as $p) {
            Pertanyaan::create([
                'isi_pertanyaan' => $p['isi_pertanyaan'],
                'pilihan_jawaban' => $p['pilihan_jawaban'],
                'kategori' => $p['kategori'],
                'urutan' => $p['urutan'],
            ]);
        }
    }
}
