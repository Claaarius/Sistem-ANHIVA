<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'nama_admin',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'Super Admin';
    }

    public function catatanKonseling()
    {
        return $this->hasMany(CatatanKonseling::class, 'id_admin', 'id_admin');
    }

    public function materiEdukasi()
    {
        return $this->hasMany(MateriEdukasi::class, 'id_admin', 'id_admin');
    }

    public function gallery()
    {
        return $this->hasMany(Gallery::class, 'id_admin', 'id_admin');
    }
}
