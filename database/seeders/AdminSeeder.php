<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'nama_admin' => 'admin123',
            'email' => 'admin@anhiva.com',
            'password' => bcrypt('admin123'),
            'role' => 'Super Admin',
        ]);
    }
}
