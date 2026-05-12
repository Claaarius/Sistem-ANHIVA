<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';

    protected $fillable = [
        'id_pengguna',
        'kode_unik',
        'isi_komentar',
        'status',
        'tanggal_komentar',
        'jumlah_edit',
        'tanggal_edit_terakhir',
        'is_edited',
        'sudah_dilihat_admin',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_komentar' => 'datetime',
            'tanggal_edit_terakhir' => 'datetime',
            'is_edited' => 'boolean',
            'sudah_dilihat_admin' => 'boolean',
        ];
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Get the censored display name:
     * Anonymous: "ANH-X7K******FD"
     * Registered: "B**i"
     */
    public function getNamaTampilAttribute(): string
    {
        if ($this->pengguna) {
            return $this->pengguna->nama_tampil;
        }
        // Anonymous: use sensored kode_unik
        return Pengguna::sensorKodeUnik($this->kode_unik);
    }

    public function canEdit(): bool
    {
        return $this->jumlah_edit < 2;
    }
}
