<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konseling extends Model
{
    protected $table = 'konseling';
    protected $primaryKey = 'id_konseling';

    protected $fillable = [
        'id_pengguna',
        'kode_unik',
        'jenis_kelamin',
        'alasan',
        'jenis',
        'nomor_kontak',
        'status',
        'tanggal_pengajuan',
        'jadwal_konseling',
        'lokasi_konseling',
        'sudah_dilihat_admin',
        'konfirmasi_pengguna',
        'catatan_reschedule',
        'tanggal_reschedule_diminta',
        'jumlah_reschedule',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'datetime',
            'jadwal_konseling' => 'datetime',
            'tanggal_reschedule_diminta' => 'datetime',
            'sudah_dilihat_admin' => 'boolean',
        ];
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function catatanKonseling()
    {
        return $this->hasMany(CatatanKonseling::class, 'id_konseling', 'id_konseling');
    }

    public function rujukan()
    {
        return $this->hasMany(Rujukan::class, 'id_konseling', 'id_konseling');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Menunggu' => 'badge-status-menunggu',
            'Menunggu Reschedule' => 'badge-status-menunggu',
            'Dijadwalkan' => 'badge-status-dijadwalkan',
            'Selesai' => 'badge-status-selesai',
            default => 'badge-status-menunggu',
        };
    }
}
