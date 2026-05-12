<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konseling', function (Blueprint $table) {
            $table->dateTime('tanggal_reschedule_diminta')->nullable()->after('catatan_reschedule');
            $table->integer('jumlah_reschedule')->default(0)->after('tanggal_reschedule_diminta');
        });

        DB::statement("ALTER TABLE konseling MODIFY COLUMN status ENUM('Menunggu','Dijadwalkan','Selesai','Menunggu Reschedule') DEFAULT 'Menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE konseling MODIFY COLUMN status ENUM('Menunggu','Dijadwalkan','Selesai') DEFAULT 'Menunggu'");

        Schema::table('konseling', function (Blueprint $table) {
            $table->dropColumn(['tanggal_reschedule_diminta', 'jumlah_reschedule']);
        });
    }
};
