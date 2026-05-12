<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriEdukasi extends Model
{
    protected $table = 'materi_edukasi';
    protected $primaryKey = 'id_materi';

    protected $fillable = [
        'id_admin',
        'judul',
        'isi_materi',
        'kategori',
        'thumbnail',
        'tanggal_publish',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_publish' => 'datetime',
        ];
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    public function getRingkasanAttribute(): string
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->isi_materi), 150);
    }
}
