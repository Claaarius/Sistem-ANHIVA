<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Pertanyaan;
use App\Models\MateriEdukasi;
use App\Models\DashboardKonten;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            PertanyaanSeeder::class,
            MateriEdukasiSeeder::class,
            DashboardKontenSeeder::class,
        ]);
    }
}
