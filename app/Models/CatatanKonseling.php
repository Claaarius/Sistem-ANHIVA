<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanKonseling extends Model
{
    protected $table = 'catatan_konseling';
    protected $primaryKey = 'id_catatan';

    protected $fillable = [
        'id_konseling',
        'id_admin',
        'kode_unik',
        'tanggal_catatan',
        'deskripsi_hasil',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_catatan' => 'datetime',
        ];
    }

    public function konseling()
    {
        return $this->belongsTo(Konseling::class, 'id_konseling', 'id_konseling');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
