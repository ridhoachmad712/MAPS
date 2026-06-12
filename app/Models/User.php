<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password_hash' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Kolom kata sandi mengikuti ERD (password_hash, bukan password).
     */
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class, 'user_id', 'user_id');
    }

    public function verifikasi(): HasMany
    {
        return $this->hasMany(Verifikasi::class, 'verifikator_id', 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVerifikator(): bool
    {
        return $this->role === 'verifikator';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }
}
