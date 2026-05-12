<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'kode_unik',
        'username',
        'email',
        'password',
        'tanggal_daftar',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'tanggal_daftar' => 'datetime',
        ];
    }

    public function skrining()
    {
        return $this->hasMany(Skrining::class, 'id_pengguna', 'id_pengguna');
    }

    public function konseling()
    {
        return $this->hasMany(Konseling::class, 'id_pengguna', 'id_pengguna');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Get censored username for display: "B**i"
     */
    public function getNamaTampilAttribute(): string
    {
        $name = $this->username;
        if (strlen($name) <= 2) {
            return $name[0] . '*';
        }
        return $name[0] . str_repeat('*', strlen($name) - 2) . $name[strlen($name) - 1];
    }

    /**
     * Get censored unique code: "ANH-TX******FD"
     */
    public static function sensorKodeUnik($kode)
    {
        if (!$kode) return null;
        $len = strlen($kode);
        if ($len <= 8) {
            $prefix = substr($kode, 0, 4);
            $suffix = substr($kode, -2);
            $stars = max(2, $len - 6);
            return $prefix . str_repeat('*', $stars) . $suffix;
        }
        $prefix = substr($kode, 0, 6); // "ANH-TX"
        $suffix = substr($kode, -2); // "FD"
        $stars = max(6, $len - 8);
        return $prefix . str_repeat('*', $stars) . $suffix;
    }

    public function getKodeUnikTampilAttribute(): ?string
    {
        return self::sensorKodeUnik($this->kode_unik);
    }
}
