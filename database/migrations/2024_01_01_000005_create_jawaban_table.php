<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban', function (Blueprint $table) {
            $table->id('id_jawaban');
            $table->unsignedBigInteger('id_skrining');
            $table->unsignedBigInteger('id_pertanyaan');
            $table->text('pilihan_jawaban');
            $table->integer('skor_kontribusi')->default(0);
            $table->timestamps();

            $table->foreign('id_skrining')->references('id_skrining')->on('skrining')->onDelete('cascade');
            $table->foreign('id_pertanyaan')->references('id_pertanyaan')->on('pertanyaan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban');
    }
};
