<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_konseling', function (Blueprint $table) {
            $table->id('id_catatan');
            $table->unsignedBigInteger('id_konseling');
            $table->unsignedBigInteger('id_admin');
            $table->string('kode_unik', 10);
            $table->timestamp('tanggal_catatan')->useCurrent();
            $table->text('deskripsi_hasil');
            $table->timestamps();

            $table->foreign('id_konseling')->references('id_konseling')->on('konseling')->onDelete('cascade');
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('cascade');
            $table->index('kode_unik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_konseling');
    }
};
