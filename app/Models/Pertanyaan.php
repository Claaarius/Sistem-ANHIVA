<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    protected $table = 'pertanyaan';
    protected $primaryKey = 'id_pertanyaan';

    protected $fillable = [
        'isi_pertanyaan',
        'pilihan_jawaban',
        'kategori',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'pilihan_jawaban' => 'array',
        ];
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'id_pertanyaan', 'id_pertanyaan');
    }
}
