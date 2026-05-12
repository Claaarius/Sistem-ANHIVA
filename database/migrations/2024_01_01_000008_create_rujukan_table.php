<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rujukan', function (Blueprint $table) {
            $table->id('id_rujukan');
            $table->unsignedBigInteger('id_konseling');
            $table->string('lokasi_rujukan');
            $table->date('tanggal_rujukan');
            $table->enum('status', ['Aktif', 'Selesai', 'Dibatalkan'])->default('Aktif');
            $table->timestamps();

            $table->foreign('id_konseling')->references('id_konseling')->on('konseling')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rujukan');
    }
};
