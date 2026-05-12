<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komentar', function (Blueprint $table) {
            $table->id('id_komentar');
            $table->unsignedBigInteger('id_pengguna')->nullable();
            $table->string('kode_unik', 10);
            $table->text('isi_komentar');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->timestamp('tanggal_komentar')->useCurrent();
            $table->integer('jumlah_edit')->default(0);
            $table->timestamp('tanggal_edit_terakhir')->nullable();
            $table->boolean('is_edited')->default(false);
            $table->boolean('sudah_dilihat_admin')->default(false);
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('set null');
            $table->index('kode_unik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar');
    }
};
