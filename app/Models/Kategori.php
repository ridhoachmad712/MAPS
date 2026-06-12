<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $primaryKey = 'kategori_id';

    protected $fillable = [
        'kode',
        'nama_kategori',
        'deskripsi',
    ];

    public function portofolio(): HasMany
    {
        return $this->hasMany(Portofolio::class, 'kategori_id', 'kategori_id');
    }
}
