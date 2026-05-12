<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_edukasi', function (Blueprint $table) {
            $table->id('id_materi');
            $table->unsignedBigInteger('id_admin');
            $table->string('judul');
            $table->longText('isi_materi');
            $table->string('kategori');
            $table->string('thumbnail')->nullable();
            $table->timestamp('tanggal_publish')->useCurrent();
            $table->timestamps();

            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_edukasi');
    }
};
