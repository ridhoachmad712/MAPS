<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $primaryKey = 'mahasiswa_id';

    protected $fillable = [
        'user_id',
        'nim',
        'nama_lengkap',
        'angkatan',
        'program_studi',
        'foto',
        'konsen_publik',
    ];

    protected function casts(): array
    {
        return [
            'konsen_publik' => 'boolean',
            'angkatan' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function portofolio(): HasMany
    {
        return $this->hasMany(Portofolio::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    /**
     * NIM disamarkan untuk halaman publik: 4 digit awal + *** + 3 digit akhir.
     */
    public function nimSamar(): string
    {
        if (strlen($this->nim) <= 7) {
            return substr($this->nim, 0, 2).'***';
        }

        return substr($this->nim, 0, 4).'***'.substr($this->nim, -3);
    }
}
