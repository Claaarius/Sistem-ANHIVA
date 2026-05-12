<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardKonten extends Model
{
    protected $table = 'dashboard_konten';
    protected $primaryKey = 'id_konten';

    protected $fillable = [
        'tipe',
        'judul',
        'konten',
        'sumber',
        'gambar',
        'tombol_teks',
        'tombol_link',
        'urutan',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function scopeHero($query)
    {
        return $query->where('tipe', 'hero')->where('aktif', true);
    }

    public function scopeFaktaHiv($query)
    {
        return $query->where('tipe', 'fakta_hiv')->where('aktif', true)->orderBy('urutan');
    }
}
