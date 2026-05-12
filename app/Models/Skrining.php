<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skrining extends Model
{
    protected $table = 'skrining';
    protected $primaryKey = 'id_skrining';

    protected $fillable = [
        'id_pengguna',
        'kode_unik',
        'tanggal_skrining',
        'skor_total',
        'tingkat_risiko',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_skrining' => 'datetime',
        ];
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'id_skrining', 'id_skrining');
    }

    public function getRisikoBadgeClassAttribute(): string
    {
        return match ($this->tingkat_risiko) {
            'Rendah' => 'badge-risiko-rendah',
            'Sedang' => 'badge-risiko-sedang',
            'Tinggi' => 'badge-risiko-tinggi',
            default => 'badge-risiko-rendah',
        };
    }
}
