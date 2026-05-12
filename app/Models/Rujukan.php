<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rujukan extends Model
{
    protected $table = 'rujukan';
    protected $primaryKey = 'id_rujukan';

    protected $fillable = [
        'id_konseling',
        'lokasi_rujukan',
        'tanggal_rujukan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_rujukan' => 'date',
        ];
    }

    public function konseling()
    {
        return $this->belongsTo(Konseling::class, 'id_konseling', 'id_konseling');
    }
}
