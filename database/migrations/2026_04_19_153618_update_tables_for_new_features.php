<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->string('kode_unik', 15)->change();
            $table->string('foto_profil', 255)->nullable();
        });

        Schema::table('konseling', function (Blueprint $table) {
            $table->string('kode_unik', 15)->nullable()->change();
            $table->enum('konfirmasi_pengguna', ['Menunggu Konfirmasi', 'Dikonfirmasi', 'Minta Reschedule'])->nullable()->default(null);
            $table->text('catatan_reschedule')->nullable();
        });

        Schema::table('komentar', function (Blueprint $table) {
            $table->string('kode_unik', 15)->nullable()->change();
        });

        Schema::table('catatan_konseling', function (Blueprint $table) {
            $table->string('kode_unik', 15)->nullable()->change();
        });

        Schema::table('skrining', function (Blueprint $table) {
            $table->string('kode_unik', 15)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
            $table->string('kode_unik', 10)->change();
        });

        Schema::table('konseling', function (Blueprint $table) {
            $table->dropColumn(['konfirmasi_pengguna', 'catatan_reschedule']);
            $table->string('kode_unik', 10)->nullable()->change();
        });

        Schema::table('komentar', function (Blueprint $table) {
            $table->string('kode_unik', 10)->nullable()->change();
        });

        Schema::table('catatan_konseling', function (Blueprint $table) {
            $table->string('kode_unik', 10)->nullable()->change();
        });

        Schema::table('skrining', function (Blueprint $table) {
            $table->string('kode_unik', 10)->nullable()->change();
        });
    }
};
