<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MateriEdukasi;
use App\Models\Admin;

class MateriEdukasiSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();
        $materi = [
            [
                'judul' => 'Apa Itu HIV dan AIDS?',
                'kategori' => 'Pengetahuan Dasar',
                'isi_materi' => '<h2>Pengertian HIV</h2><p>HIV (Human Immunodeficiency Virus) adalah virus yang menyerang sistem kekebalan tubuh manusia, khususnya sel CD4 (sel T). Jika tidak diobati, HIV dapat mengurangi jumlah sel CD4 dalam tubuh sehingga kekebalan tubuh melemah.</p><h2>Pengertian AIDS</h2><p>AIDS (Acquired Immunodeficiency Syndrome) adalah tahap lanjut dari infeksi HIV, di mana sistem kekebalan tubuh sudah rusak parah dan tubuh tidak mampu melawan infeksi serta penyakit tertentu.</p><h2>Perbedaan HIV dan AIDS</h2><p>HIV adalah virus penyebab penyakit, sedangkan AIDS adalah kondisi yang muncul akibat infeksi HIV yang tidak diobati. Seseorang bisa hidup bertahun-tahun dengan HIV tanpa terjadi AIDS, terutama jika menjalani pengobatan antiretroviral (ARV) secara teratur.</p><h2>Bagaimana HIV Menyerang Tubuh?</h2><p>HIV masuk ke dalam tubuh dan menginfeksi sel CD4. Virus ini menggunakan sel CD4 untuk memperbanyak diri, kemudian menghancurkan sel tersebut. Tanpa pengobatan, jumlah sel CD4 akan terus menurun, membuat tubuh rentan terhadap infeksi oportunistik.</p>',
                'tanggal_publish' => now(),
            ],
            [
                'judul' => 'Cara Penularan HIV',
                'kategori' => 'Pengetahuan Dasar',
                'isi_materi' => '<h2>Media Penularan HIV</h2><p>HIV ditularkan melalui cairan tubuh tertentu dari seseorang yang terinfeksi, yaitu:</p><ul><li><strong>Darah</strong></li><li><strong>Air mani (sperma) dan cairan pre-ejakulasi</strong></li><li><strong>Cairan vagina dan rektal</strong></li><li><strong>Air Susu Ibu (ASI)</strong></li></ul><h2>Cara Penularan Utama</h2><p>1. <strong>Hubungan seksual tanpa kondom</strong> — baik vaginal, anal, maupun oral dengan seseorang yang terinfeksi HIV.</p><p>2. <strong>Berbagi jarum suntik</strong> — penggunaan jarum suntik bersama, terutama pada pengguna narkoba suntik.</p><p>3. <strong>Dari ibu ke anak</strong> — selama kehamilan, persalinan, atau menyusui.</p><p>4. <strong>Transfusi darah yang terkontaminasi</strong> — meskipun risiko ini sangat rendah di fasilitas kesehatan resmi.</p><h2>HIV TIDAK Ditularkan Melalui</h2><p>HIV tidak ditularkan melalui: jabat tangan, pelukan, ciuman, berbagi makanan, gigitan nyamuk, keringat, air mata, penggunaan toilet bersama, atau kontak sosial sehari-hari.</p>',
                'tanggal_publish' => now(),
            ],
            [
                'judul' => 'Pencegahan HIV yang Efektif',
                'kategori' => 'Pencegahan',
                'isi_materi' => '<h2>Langkah-Langkah Pencegahan</h2><p>Pencegahan HIV dapat dilakukan melalui beberapa cara:</p><h3>1. Penggunaan Kondom</h3><p>Selalu gunakan kondom dengan benar dan konsisten saat berhubungan seksual. Kondom merupakan salah satu cara paling efektif untuk mencegah penularan HIV melalui hubungan seksual.</p><h3>2. Setia pada Satu Pasangan</h3><p>Memiliki satu pasangan seksual yang status HIV-nya diketahui negatif dapat mengurangi risiko penularan secara signifikan.</p><h3>3. Tidak Berbagi Jarum Suntik</h3><p>Jangan pernah berbagi jarum suntik atau alat suntik lainnya dengan orang lain.</p><h3>4. PrEP (Pre-Exposure Prophylaxis)</h3><p>PrEP adalah obat yang diminum setiap hari oleh seseorang yang belum terinfeksi HIV untuk mencegah infeksi. PrEP sangat efektif jika diminum secara teratur.</p><h3>5. Tes HIV Rutin</h3><p>Lakukan tes HIV secara rutin, terutama jika Anda memiliki faktor risiko. Mengetahui status dini memungkinkan penanganan lebih cepat.</p>',
                'tanggal_publish' => now(),
            ],
            [
                'judul' => 'Mitos dan Fakta Seputar HIV/AIDS',
                'kategori' => 'Mitos dan Fakta',
                'isi_materi' => '<h2>Mitos vs Fakta</h2><h3>Mitos: HIV dapat ditularkan melalui gigitan nyamuk</h3><p><strong>Fakta:</strong> HIV tidak dapat ditularkan melalui gigitan nyamuk. Virus HIV tidak dapat bertahan dan berkembang di dalam tubuh nyamuk.</p><h3>Mitos: Orang yang terlihat sehat tidak mungkin mengidap HIV</h3><p><strong>Fakta:</strong> Seseorang dengan HIV bisa terlihat sehat selama bertahun-tahun tanpa gejala. Satu-satunya cara mengetahui status HIV adalah melalui tes.</p><h3>Mitos: HIV adalah hukuman karena perilaku tertentu</h3><p><strong>Fakta:</strong> HIV adalah penyakit menular yang dapat menginfeksi siapa saja, tanpa memandang latar belakang. Stigma dan diskriminasi justru menghambat upaya pencegahan dan pengobatan.</p><h3>Mitos: HIV pasti berujung pada kematian</h3><p><strong>Fakta:</strong> Dengan pengobatan ARV yang tepat dan teratur, pengidap HIV bisa hidup normal dan panjang umur. Deteksi dini dan pengobatan adalah kunci.</p>',
                'tanggal_publish' => now(),
            ],
            [
                'judul' => 'Pengobatan HIV: Terapi Antiretroviral (ARV)',
                'kategori' => 'Pengobatan',
                'isi_materi' => '<h2>Apa Itu ARV?</h2><p>Antiretroviral (ARV) adalah obat yang digunakan untuk mengobati infeksi HIV. ARV tidak menyembuhkan HIV, tetapi dapat menekan jumlah virus (viral load) dalam tubuh sehingga sistem kekebalan tubuh dapat pulih dan berfungsi dengan baik.</p><h2>Bagaimana ARV Bekerja?</h2><p>ARV bekerja dengan cara menghambat replikasi virus HIV di dalam tubuh. Dengan terapi ARV yang konsisten, viral load dapat ditekan hingga tidak terdeteksi (undetectable), yang berarti risiko penularan juga menjadi sangat rendah.</p><h2>Kapan Harus Mulai ARV?</h2><p>Panduan WHO merekomendasikan pengobatan ARV dimulai segera setelah diagnosis HIV, tanpa menunggu jumlah CD4 turun. Semakin cepat memulai, semakin baik hasilnya.</p><h2>Efek Samping ARV</h2><p>Beberapa efek samping yang mungkin muncul termasuk mual, diare, kelelahan, dan sakit kepala. Namun, efek samping biasanya berkurang seiring waktu dan dokter dapat menyesuaikan obat jika diperlukan.</p>',
                'tanggal_publish' => now(),
            ],
        ];

        foreach ($materi as $m) {
            MateriEdukasi::create(array_merge($m, ['id_admin' => $admin->id_admin]));
        }
    }
}
