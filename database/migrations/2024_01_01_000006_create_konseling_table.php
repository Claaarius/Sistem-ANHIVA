<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konseling', function (Blueprint $table) {
            $table->id('id_konseling');
            $table->unsignedBigInteger('id_pengguna')->nullable();
            $table->string('kode_unik', 10);
            $table->text('alasan');
            $table->enum('jenis', ['Online', 'Tatap Muka']);
            $table->string('nomor_kontak')->nullable();
            $table->enum('status', ['Menunggu', 'Dijadwalkan', 'Selesai'])->default('Menunggu');
            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->datetime('jadwal_konseling')->nullable();
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('set null');
            $table->index('kode_unik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konseling');
    }
};
