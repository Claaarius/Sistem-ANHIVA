<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skrining', function (Blueprint $table) {
            $table->id('id_skrining');
            $table->unsignedBigInteger('id_pengguna')->nullable();
            $table->string('kode_unik', 10);
            $table->timestamp('tanggal_skrining')->useCurrent();
            $table->integer('skor_total')->default(0);
            $table->enum('tingkat_risiko', ['Rendah', 'Sedang', 'Tinggi'])->default('Rendah');
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('set null');
            $table->index('kode_unik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skrining');
    }
};
