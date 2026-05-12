<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery', function (Blueprint $table) {
            $table->id('id_gallery');
            $table->unsignedBigInteger('id_admin');
            $table->string('foto');
            $table->text('keterangan');
            $table->timestamp('tanggal_upload')->useCurrent();
            $table->timestamps();

            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery');
    }
};
