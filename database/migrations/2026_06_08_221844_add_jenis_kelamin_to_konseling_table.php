<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konseling', function (Blueprint $table) {
            $table->enum('jenis_kelamin', [
                'Laki-laki',
                'Perempuan',
                'Non-binary'
            ])->after('nomor_kontak');
        });
    }

    public function down(): void
    {
        Schema::table('konseling', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
        });
    }
};
