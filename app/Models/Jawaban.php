<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    protected $table = 'jawaban';
    protected $primaryKey = 'id_jawaban';

    protected $fillable = [
        'id_skrining',
        'id_pertanyaan',
        'pilihan_jawaban',
        'skor_kontribusi',
    ];

    public function skrining()
    {
        return $this->belongsTo(Skrining::class, 'id_skrining', 'id_skrining');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'id_pertanyaan', 'id_pertanyaan');
    }
}
