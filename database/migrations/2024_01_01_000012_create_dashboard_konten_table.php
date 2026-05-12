<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_konten', function (Blueprint $table) {
            $table->id('id_konten');
            $table->enum('tipe', ['hero', 'fakta_hiv']);
            $table->string('judul')->nullable();
            $table->text('konten')->nullable();
            $table->string('sumber')->nullable();
            $table->string('gambar')->nullable();
            $table->string('tombol_teks')->nullable();
            $table->string('tombol_link')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_konten');
    }
};
