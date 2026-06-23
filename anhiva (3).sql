-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Jun 2026 pada 03.58
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `anhiva`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` bigint(20) UNSIGNED NOT NULL,
  `nama_admin` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Super Admin','Admin') NOT NULL DEFAULT 'Admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@anhiva.com', '$2y$12$68ZZSYpfNFUJMPhAisAOUu57MhOE5vsE2IfoIynYrbaxPHNacKkgq', 'Super Admin', '2026-06-11 18:54:35', '2026-06-11 18:58:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `catatan_konseling`
--

CREATE TABLE `catatan_konseling` (
  `id_catatan` bigint(20) UNSIGNED NOT NULL,
  `id_konseling` bigint(20) UNSIGNED NOT NULL,
  `id_admin` bigint(20) UNSIGNED NOT NULL,
  `kode_unik` varchar(15) DEFAULT NULL,
  `tanggal_catatan` timestamp NOT NULL DEFAULT current_timestamp(),
  `deskripsi_hasil` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dashboard_konten`
--

CREATE TABLE `dashboard_konten` (
  `id_konten` bigint(20) UNSIGNED NOT NULL,
  `tipe` enum('hero','fakta_hiv') NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `konten` text DEFAULT NULL,
  `sumber` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tombol_teks` varchar(255) DEFAULT NULL,
  `tombol_link` varchar(255) DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dashboard_konten`
--

INSERT INTO `dashboard_konten` (`id_konten`, `tipe`, `judul`, `konten`, `sumber`, `gambar`, `tombol_teks`, `tombol_link`, `urutan`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 'hero', 'ANHIVA', 'Sistem Edukasi dan Skrining Risiko HIV Berbasis Anonim. Lindungi diri Anda dengan pengetahuan yang tepat dan skrining risiko secara anonim.', NULL, NULL, 'Mulai Skrining', '/skrining', 1, 1, '2026-06-11 18:54:36', '2026-06-11 18:54:36'),
(2, 'fakta_hiv', 'Jumlah ODHIV di Indonesia', 'Hingga Desember 2023, tercatat lebih dari 519.000 orang terdiagnosis HIV di Indonesia. Jumlah ini terus meningkat setiap tahunnya.', 'Kemenkes RI, 2023', NULL, NULL, NULL, 1, 1, '2026-06-11 18:54:36', '2026-06-11 18:54:36'),
(3, 'fakta_hiv', 'Pengobatan ARV Gratis', 'Pemerintah Indonesia menyediakan obat Antiretroviral (ARV) secara gratis di seluruh fasilitas kesehatan pemerintah bagi semua ODHIV.', 'Kemenkes RI', NULL, NULL, NULL, 2, 1, '2026-06-11 18:54:36', '2026-06-11 18:54:36'),
(4, 'fakta_hiv', 'Deteksi Dini Menyelamatkan', 'Dengan terapi ARV yang dimulai sejak dini, harapan hidup ODHIV hampir sama dengan orang yang tidak terinfeksi HIV.', 'WHO, 2023', NULL, NULL, NULL, 3, 1, '2026-06-11 18:54:36', '2026-06-11 18:54:36'),
(5, 'fakta_hiv', 'Target Global UNAIDS', 'Target 95-95-95: 95% ODHIV mengetahui statusnya, 95% menerima ARV, dan 95% memiliki viral load tersupresi pada tahun 2030.', 'UNAIDS', NULL, NULL, NULL, 4, 1, '2026-06-11 18:54:36', '2026-06-11 18:54:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gallery`
--

CREATE TABLE `gallery` (
  `id_gallery` bigint(20) UNSIGNED NOT NULL,
  `id_admin` bigint(20) UNSIGNED NOT NULL,
  `foto` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jawaban`
--

CREATE TABLE `jawaban` (
  `id_jawaban` bigint(20) UNSIGNED NOT NULL,
  `id_skrining` bigint(20) UNSIGNED NOT NULL,
  `id_pertanyaan` bigint(20) UNSIGNED NOT NULL,
  `pilihan_jawaban` text NOT NULL,
  `skor_kontribusi` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `id_komentar` bigint(20) UNSIGNED NOT NULL,
  `id_pengguna` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_unik` varchar(15) DEFAULT NULL,
  `isi_komentar` text NOT NULL,
  `status` enum('Menunggu','Disetujui','Ditolak') NOT NULL DEFAULT 'Menunggu',
  `tanggal_komentar` timestamp NOT NULL DEFAULT current_timestamp(),
  `jumlah_edit` int(11) NOT NULL DEFAULT 0,
  `tanggal_edit_terakhir` timestamp NULL DEFAULT NULL,
  `is_edited` tinyint(1) NOT NULL DEFAULT 0,
  `sudah_dilihat_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `konseling`
--

CREATE TABLE `konseling` (
  `id_konseling` bigint(20) UNSIGNED NOT NULL,
  `id_pengguna` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_unik` varchar(15) DEFAULT NULL,
  `alasan` text NOT NULL,
  `jenis` enum('Online','Tatap Muka') NOT NULL,
  `nomor_kontak` varchar(255) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan','Non-binary') NOT NULL,
  `status` enum('Menunggu','Dijadwalkan','Selesai','Menunggu Reschedule') DEFAULT 'Menunggu',
  `sudah_dilihat_admin` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT current_timestamp(),
  `jadwal_konseling` datetime DEFAULT NULL,
  `lokasi_konseling` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `konfirmasi_pengguna` enum('Menunggu Konfirmasi','Dikonfirmasi','Minta Reschedule') DEFAULT NULL,
  `catatan_reschedule` text DEFAULT NULL,
  `tanggal_reschedule_diminta` datetime DEFAULT NULL,
  `jumlah_reschedule` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `materi_edukasi`
--

CREATE TABLE `materi_edukasi` (
  `id_materi` bigint(20) UNSIGNED NOT NULL,
  `id_admin` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi_materi` longtext NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `tampilkan_di_dashboard` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal_publish` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `materi_edukasi`
--

INSERT INTO `materi_edukasi` (`id_materi`, `id_admin`, `judul`, `isi_materi`, `kategori`, `thumbnail`, `tampilkan_di_dashboard`, `tanggal_publish`, `created_at`, `updated_at`) VALUES
(1, 1, 'Apa Itu HIV dan AIDS?', '<h2>Pengertian HIV</h2><p>HIV (Human Immunodeficiency Virus) adalah virus yang menyerang sistem kekebalan tubuh manusia, khususnya sel CD4 (sel T). Jika tidak diobati, HIV dapat mengurangi jumlah sel CD4 dalam tubuh sehingga kekebalan tubuh melemah.</p><h2>Pengertian AIDS</h2><p>AIDS (Acquired Immunodeficiency Syndrome) adalah tahap lanjut dari infeksi HIV, di mana sistem kekebalan tubuh sudah rusak parah dan tubuh tidak mampu melawan infeksi serta penyakit tertentu.</p><h2>Perbedaan HIV dan AIDS</h2><p>HIV adalah virus penyebab penyakit, sedangkan AIDS adalah kondisi yang muncul akibat infeksi HIV yang tidak diobati. Seseorang bisa hidup bertahun-tahun dengan HIV tanpa terjadi AIDS, terutama jika menjalani pengobatan antiretroviral (ARV) secara teratur.</p><h2>Bagaimana HIV Menyerang Tubuh?</h2><p>HIV masuk ke dalam tubuh dan menginfeksi sel CD4. Virus ini menggunakan sel CD4 untuk memperbanyak diri, kemudian menghancurkan sel tersebut. Tanpa pengobatan, jumlah sel CD4 akan terus menurun, membuat tubuh rentan terhadap infeksi oportunistik.</p>', 'Pengetahuan Dasar', NULL, 0, '2026-06-11 18:54:36', '2026-06-11 18:54:36', '2026-06-11 18:54:36'),
(2, 1, 'Cara Penularan HIV', '<h2>Media Penularan HIV</h2><p>HIV ditularkan melalui cairan tubuh tertentu dari seseorang yang terinfeksi, yaitu:</p><ul><li><strong>Darah</strong></li><li><strong>Air mani (sperma) dan cairan pre-ejakulasi</strong></li><li><strong>Cairan vagina dan rektal</strong></li><li><strong>Air Susu Ibu (ASI)</strong></li></ul><h2>Cara Penularan Utama</h2><p>1. <strong>Hubungan seksual tanpa kondom</strong> — baik vaginal, anal, maupun oral dengan seseorang yang terinfeksi HIV.</p><p>2. <strong>Berbagi jarum suntik</strong> — penggunaan jarum suntik bersama, terutama pada pengguna narkoba suntik.</p><p>3. <strong>Dari ibu ke anak</strong> — selama kehamilan, persalinan, atau menyusui.</p><p>4. <strong>Transfusi darah yang terkontaminasi</strong> — meskipun risiko ini sangat rendah di fasilitas kesehatan resmi.</p><h2>HIV TIDAK Ditularkan Melalui</h2><p>HIV tidak ditularkan melalui: jabat tangan, pelukan, ciuman, berbagi makanan, gigitan nyamuk, keringat, air mata, penggunaan toilet bersama, atau kontak sosial sehari-hari.</p>', 'Pengetahuan Dasar', NULL, 0, '2026-06-11 18:54:36', '2026-06-11 18:54:36', '2026-06-11 18:54:36'),
(3, 1, 'Pencegahan HIV yang Efektif', '<h2>Langkah-Langkah Pencegahan</h2><p>Pencegahan HIV dapat dilakukan melalui beberapa cara:</p><h3>1. Penggunaan Kondom</h3><p>Selalu gunakan kondom dengan benar dan konsisten saat berhubungan seksual. Kondom merupakan salah satu cara paling efektif untuk mencegah penularan HIV melalui hubungan seksual.</p><h3>2. Setia pada Satu Pasangan</h3><p>Memiliki satu pasangan seksual yang status HIV-nya diketahui negatif dapat mengurangi risiko penularan secara signifikan.</p><h3>3. Tidak Berbagi Jarum Suntik</h3><p>Jangan pernah berbagi jarum suntik atau alat suntik lainnya dengan orang lain.</p><h3>4. PrEP (Pre-Exposure Prophylaxis)</h3><p>PrEP adalah obat yang diminum setiap hari oleh seseorang yang belum terinfeksi HIV untuk mencegah infeksi. PrEP sangat efektif jika diminum secara teratur.</p><h3>5. Tes HIV Rutin</h3><p>Lakukan tes HIV secara rutin, terutama jika Anda memiliki faktor risiko. Mengetahui status dini memungkinkan penanganan lebih cepat.</p>', 'Pencegahan', NULL, 0, '2026-06-11 18:54:36', '2026-06-11 18:54:36', '2026-06-11 18:54:36'),
(4, 1, 'Mitos dan Fakta Seputar HIV/AIDS', '<h2>Mitos vs Fakta</h2><h3>Mitos: HIV dapat ditularkan melalui gigitan nyamuk</h3><p><strong>Fakta:</strong> HIV tidak dapat ditularkan melalui gigitan nyamuk. Virus HIV tidak dapat bertahan dan berkembang di dalam tubuh nyamuk.</p><h3>Mitos: Orang yang terlihat sehat tidak mungkin mengidap HIV</h3><p><strong>Fakta:</strong> Seseorang dengan HIV bisa terlihat sehat selama bertahun-tahun tanpa gejala. Satu-satunya cara mengetahui status HIV adalah melalui tes.</p><h3>Mitos: HIV adalah hukuman karena perilaku tertentu</h3><p><strong>Fakta:</strong> HIV adalah penyakit menular yang dapat menginfeksi siapa saja, tanpa memandang latar belakang. Stigma dan diskriminasi justru menghambat upaya pencegahan dan pengobatan.</p><h3>Mitos: HIV pasti berujung pada kematian</h3><p><strong>Fakta:</strong> Dengan pengobatan ARV yang tepat dan teratur, pengidap HIV bisa hidup normal dan panjang umur. Deteksi dini dan pengobatan adalah kunci.</p>', 'Mitos dan Fakta', NULL, 0, '2026-06-11 18:54:36', '2026-06-11 18:54:36', '2026-06-11 18:54:36'),
(5, 1, 'Pengobatan HIV: Terapi Antiretroviral (ARV)', '<h2>Apa Itu ARV?</h2><p>Antiretroviral (ARV) adalah obat yang digunakan untuk mengobati infeksi HIV. ARV tidak menyembuhkan HIV, tetapi dapat menekan jumlah virus (viral load) dalam tubuh sehingga sistem kekebalan tubuh dapat pulih dan berfungsi dengan baik.</p><h2>Bagaimana ARV Bekerja?</h2><p>ARV bekerja dengan cara menghambat replikasi virus HIV di dalam tubuh. Dengan terapi ARV yang konsisten, viral load dapat ditekan hingga tidak terdeteksi (undetectable), yang berarti risiko penularan juga menjadi sangat rendah.</p><h2>Kapan Harus Mulai ARV?</h2><p>Panduan WHO merekomendasikan pengobatan ARV dimulai segera setelah diagnosis HIV, tanpa menunggu jumlah CD4 turun. Semakin cepat memulai, semakin baik hasilnya.</p><h2>Efek Samping ARV</h2><p>Beberapa efek samping yang mungkin muncul termasuk mual, diare, kelelahan, dan sakit kepala. Namun, efek samping biasanya berkurang seiring waktu dan dokter dapat menyesuaikan obat jika diperlukan.</p>', 'Pengobatan', NULL, 0, '2026-06-11 18:54:36', '2026-06-11 18:54:36', '2026-06-11 18:54:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_01_000001_create_pengguna_table', 1),
(2, '2024_01_01_000002_create_admin_table', 1),
(3, '2024_01_01_000003_create_pertanyaan_table', 1),
(4, '2024_01_01_000004_create_skrining_table', 1),
(5, '2024_01_01_000005_create_jawaban_table', 1),
(6, '2024_01_01_000006_create_konseling_table', 1),
(7, '2024_01_01_000007_create_catatan_konseling_table', 1),
(8, '2024_01_01_000008_create_rujukan_table', 1),
(9, '2024_01_01_000009_create_materi_edukasi_table', 1),
(10, '2024_01_01_000010_create_komentar_table', 1),
(11, '2024_01_01_000011_create_gallery_table', 1),
(12, '2024_01_01_000012_create_dashboard_konten_table', 1),
(13, '2026_04_16_105116_add_sudah_dilihat_admin_to_konseling_table', 1),
(14, '2026_04_19_153618_update_tables_for_new_features', 1),
(15, '2026_04_20_190000_add_reschedule_fields_to_konseling', 1),
(16, '2026_04_21_183400_add_lokasi_konseling_to_konseling_table', 1),
(17, '2026_05_12_015632_create_sessions_table', 2),
(18, '2026_05_19_033108_add_tampilkan_di_dashboard_to_materi_edukasi_table', 2),
(19, '2026_06_08_221844_add_jenis_kelamin_to_konseling_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` bigint(20) UNSIGNED NOT NULL,
  `kode_unik` varchar(15) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertanyaan`
--

CREATE TABLE `pertanyaan` (
  `id_pertanyaan` bigint(20) UNSIGNED NOT NULL,
  `isi_pertanyaan` text NOT NULL,
  `pilihan_jawaban` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`pilihan_jawaban`)),
  `kategori` varchar(255) NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pertanyaan`
--

INSERT INTO `pertanyaan` (`id_pertanyaan`, `isi_pertanyaan`, `pilihan_jawaban`, `kategori`, `urutan`, `created_at`, `updated_at`) VALUES
(1, 'Apakah Anda pernah melakukan hubungan seksual tanpa menggunakan kondom?', '[{\"teks\":\"Tidak pernah\",\"bobot\":0},{\"teks\":\"Pernah, 1-2 kali\",\"bobot\":2},{\"teks\":\"Pernah, lebih dari 2 kali\",\"bobot\":4},{\"teks\":\"Sering \\/ rutin\",\"bobot\":6}]', 'Perilaku Seksual', 1, '2026-06-11 18:54:35', '2026-06-11 18:54:35'),
(2, 'Berapa jumlah pasangan seksual yang Anda miliki dalam 12 bulan terakhir?', '[{\"teks\":\"Tidak ada\",\"bobot\":0},{\"teks\":\"1 orang (pasangan tetap)\",\"bobot\":1},{\"teks\":\"2-5 orang\",\"bobot\":4},{\"teks\":\"Lebih dari 5 orang\",\"bobot\":6}]', 'Perilaku Seksual', 2, '2026-06-11 18:54:35', '2026-06-11 18:54:35'),
(3, 'Apakah Anda pernah didiagnosis atau mengalami gejala Infeksi Menular Seksual (IMS) seperti sifilis, gonore, klamidia, atau herpes genital?', '[{\"teks\":\"Tidak pernah\",\"bobot\":0},{\"teks\":\"Pernah, sudah diobati\",\"bobot\":3},{\"teks\":\"Pernah, tidak\\/belum diobati\",\"bobot\":5},{\"teks\":\"Tidak tahu \\/ tidak pernah diperiksa\",\"bobot\":2}]', 'Riwayat Kesehatan', 3, '2026-06-11 18:54:35', '2026-06-11 18:54:35'),
(4, 'Apakah Anda pernah menggunakan narkoba suntik atau berbagi jarum suntik dengan orang lain?', '[{\"teks\":\"Tidak pernah\",\"bobot\":0},{\"teks\":\"Pernah, tapi sudah berhenti\",\"bobot\":4},{\"teks\":\"Masih aktif menggunakan\",\"bobot\":7}]', 'Penggunaan Narkoba', 4, '2026-06-11 18:54:35', '2026-06-11 18:54:35'),
(5, 'Apakah Anda pernah menerima transfusi darah atau produk darah yang tidak diketahui status skriningnya?', '[{\"teks\":\"Tidak pernah\",\"bobot\":0},{\"teks\":\"Pernah, di fasilitas kesehatan resmi\",\"bobot\":1},{\"teks\":\"Pernah, di tempat yang tidak jelas\\/informal\",\"bobot\":5},{\"teks\":\"Tidak yakin \\/ tidak ingat\",\"bobot\":2}]', 'Riwayat Kesehatan', 5, '2026-06-11 18:54:35', '2026-06-11 18:54:35'),
(6, 'Apakah Anda mengetahui status HIV dari pasangan seksual Anda saat ini atau sebelumnya?', '[{\"teks\":\"Ya, negatif (sudah dites)\",\"bobot\":0},{\"teks\":\"Ya, positif HIV\",\"bobot\":6},{\"teks\":\"Tidak tahu \\/ tidak pernah ditanyakan\",\"bobot\":3},{\"teks\":\"Tidak memiliki pasangan\",\"bobot\":0}]', 'Perilaku Seksual', 6, '2026-06-11 18:54:35', '2026-06-11 18:54:35'),
(7, 'Apakah Anda pernah menjalani tes HIV sebelumnya?', '[{\"teks\":\"Ya, dalam 6 bulan terakhir (hasil negatif)\",\"bobot\":0},{\"teks\":\"Ya, lebih dari 6 bulan lalu (hasil negatif)\",\"bobot\":1},{\"teks\":\"Ya, hasilnya positif\",\"bobot\":0},{\"teks\":\"Belum pernah\",\"bobot\":2}]', 'Riwayat Tes', 7, '2026-06-11 18:54:35', '2026-06-11 18:54:35'),
(8, 'Apakah Anda pernah mengalami gejala-gejala berikut secara bersamaan dalam waktu lama: demam berkepanjangan, penurunan berat badan drastis, diare kronis, atau pembengkakan kelenjar getah bening?', '[{\"teks\":\"Tidak pernah\",\"bobot\":0},{\"teks\":\"Pernah mengalami 1-2 gejala di atas\",\"bobot\":2},{\"teks\":\"Pernah mengalami 3 atau lebih gejala di atas\",\"bobot\":5},{\"teks\":\"Sedang mengalami saat ini\",\"bobot\":6}]', 'Gejala Klinis', 8, '2026-06-11 18:54:35', '2026-06-11 18:54:35'),
(9, 'Apakah pekerjaan atau aktivitas Anda melibatkan potensi paparan cairan tubuh orang lain (misalnya: tenaga kesehatan, petugas laboratorium)?', '[{\"teks\":\"Tidak\",\"bobot\":0},{\"teks\":\"Ya, dengan alat pelindung diri lengkap\",\"bobot\":1},{\"teks\":\"Ya, tanpa alat pelindung diri yang memadai\",\"bobot\":4}]', 'Paparan Risiko', 9, '2026-06-11 18:54:35', '2026-06-11 18:54:35'),
(10, 'Apakah Anda pernah melakukan tindikan, tato, atau prosedur medis menggunakan alat yang tidak steril?', '[{\"teks\":\"Tidak pernah\",\"bobot\":0},{\"teks\":\"Pernah, di tempat yang terpercaya\\/steril\",\"bobot\":1},{\"teks\":\"Pernah, di tempat yang tidak jelas kebersihannya\",\"bobot\":4},{\"teks\":\"Tidak yakin tentang sterilitas alatnya\",\"bobot\":3}]', 'Paparan Risiko', 10, '2026-06-11 18:54:35', '2026-06-11 18:54:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rujukan`
--

CREATE TABLE `rujukan` (
  `id_rujukan` bigint(20) UNSIGNED NOT NULL,
  `id_konseling` bigint(20) UNSIGNED NOT NULL,
  `lokasi_rujukan` varchar(255) NOT NULL,
  `tanggal_rujukan` date NOT NULL,
  `status` enum('Aktif','Selesai','Dibatalkan') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('d6l2C2AuVbRGxbXVF6KNe5cFDzHlwuCUzm3XoQII', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNER1ZWhyQnRQTFpMakMxeGR3VGhHbVFvbjJFTnVYTGYxWG5rVVYwWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJrb2RlX3VuaWsiO3M6MTQ6IkFOSC1PQ1JHU0RFWDhHIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2tvbnNlbGluZyI7czo1OiJyb3V0ZSI7czoxNToia29uc2VsaW5nLmluZGV4Ijt9fQ==', 1781229517),
('fQsi7ejgcu3NKvDHiJvVtzpFhnCl61xS1uA6G9or', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.124.0 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU3N1Y1R4ZGtKSlFvaFB6bWdyeEtTd2VZUjl5MEtXTWo3RWh6WXdaSSI7czo5OiJrb2RlX3VuaWsiO3M6MTQ6IkFOSC1CR080RENXQjZDIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781229388);

-- --------------------------------------------------------

--
-- Struktur dari tabel `skrining`
--

CREATE TABLE `skrining` (
  `id_skrining` bigint(20) UNSIGNED NOT NULL,
  `id_pengguna` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_unik` varchar(15) DEFAULT NULL,
  `tanggal_skrining` timestamp NOT NULL DEFAULT current_timestamp(),
  `skor_total` int(11) NOT NULL DEFAULT 0,
  `tingkat_risiko` enum('Rendah','Sedang','Tinggi') NOT NULL DEFAULT 'Rendah',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `admin_email_unique` (`email`);

--
-- Indeks untuk tabel `catatan_konseling`
--
ALTER TABLE `catatan_konseling`
  ADD PRIMARY KEY (`id_catatan`),
  ADD KEY `catatan_konseling_id_konseling_foreign` (`id_konseling`),
  ADD KEY `catatan_konseling_id_admin_foreign` (`id_admin`),
  ADD KEY `catatan_konseling_kode_unik_index` (`kode_unik`);

--
-- Indeks untuk tabel `dashboard_konten`
--
ALTER TABLE `dashboard_konten`
  ADD PRIMARY KEY (`id_konten`);

--
-- Indeks untuk tabel `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id_gallery`),
  ADD KEY `gallery_id_admin_foreign` (`id_admin`);

--
-- Indeks untuk tabel `jawaban`
--
ALTER TABLE `jawaban`
  ADD PRIMARY KEY (`id_jawaban`),
  ADD KEY `jawaban_id_skrining_foreign` (`id_skrining`),
  ADD KEY `jawaban_id_pertanyaan_foreign` (`id_pertanyaan`);

--
-- Indeks untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id_komentar`),
  ADD KEY `komentar_id_pengguna_foreign` (`id_pengguna`),
  ADD KEY `komentar_kode_unik_index` (`kode_unik`);

--
-- Indeks untuk tabel `konseling`
--
ALTER TABLE `konseling`
  ADD PRIMARY KEY (`id_konseling`),
  ADD KEY `konseling_id_pengguna_foreign` (`id_pengguna`),
  ADD KEY `konseling_kode_unik_index` (`kode_unik`);

--
-- Indeks untuk tabel `materi_edukasi`
--
ALTER TABLE `materi_edukasi`
  ADD PRIMARY KEY (`id_materi`),
  ADD KEY `materi_edukasi_id_admin_foreign` (`id_admin`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `pengguna_kode_unik_unique` (`kode_unik`),
  ADD UNIQUE KEY `pengguna_username_unique` (`username`),
  ADD UNIQUE KEY `pengguna_email_unique` (`email`);

--
-- Indeks untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD PRIMARY KEY (`id_pertanyaan`);

--
-- Indeks untuk tabel `rujukan`
--
ALTER TABLE `rujukan`
  ADD PRIMARY KEY (`id_rujukan`),
  ADD KEY `rujukan_id_konseling_foreign` (`id_konseling`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `skrining`
--
ALTER TABLE `skrining`
  ADD PRIMARY KEY (`id_skrining`),
  ADD KEY `skrining_id_pengguna_foreign` (`id_pengguna`),
  ADD KEY `skrining_kode_unik_index` (`kode_unik`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `catatan_konseling`
--
ALTER TABLE `catatan_konseling`
  MODIFY `id_catatan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `dashboard_konten`
--
ALTER TABLE `dashboard_konten`
  MODIFY `id_konten` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id_gallery` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jawaban`
--
ALTER TABLE `jawaban`
  MODIFY `id_jawaban` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id_komentar` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `konseling`
--
ALTER TABLE `konseling`
  MODIFY `id_konseling` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `materi_edukasi`
--
ALTER TABLE `materi_edukasi`
  MODIFY `id_materi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  MODIFY `id_pertanyaan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `rujukan`
--
ALTER TABLE `rujukan`
  MODIFY `id_rujukan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `skrining`
--
ALTER TABLE `skrining`
  MODIFY `id_skrining` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `catatan_konseling`
--
ALTER TABLE `catatan_konseling`
  ADD CONSTRAINT `catatan_konseling_id_admin_foreign` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE,
  ADD CONSTRAINT `catatan_konseling_id_konseling_foreign` FOREIGN KEY (`id_konseling`) REFERENCES `konseling` (`id_konseling`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_id_admin_foreign` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jawaban`
--
ALTER TABLE `jawaban`
  ADD CONSTRAINT `jawaban_id_pertanyaan_foreign` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan` (`id_pertanyaan`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_id_skrining_foreign` FOREIGN KEY (`id_skrining`) REFERENCES `skrining` (`id_skrining`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `komentar_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `konseling`
--
ALTER TABLE `konseling`
  ADD CONSTRAINT `konseling_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `materi_edukasi`
--
ALTER TABLE `materi_edukasi`
  ADD CONSTRAINT `materi_edukasi_id_admin_foreign` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rujukan`
--
ALTER TABLE `rujukan`
  ADD CONSTRAINT `rujukan_id_konseling_foreign` FOREIGN KEY (`id_konseling`) REFERENCES `konseling` (`id_konseling`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `skrining`
--
ALTER TABLE `skrining`
  ADD CONSTRAINT `skrining_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
