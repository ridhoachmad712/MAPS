<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bukti extends Model
{
    protected $table = 'bukti';

    protected $primaryKey = 'bukti_id';

    public $timestamps = false;

    protected $fillable = [
        'portofolio_id',
        'nama_file',
        'path_file',
        'tipe_file',
        'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'uploaded_at' => 'datetime',
        ];
    }

    public function portofolio(): BelongsTo
    {
        return $this->belongsTo(Portofolio::class, 'portofolio_id', 'portofolio_id');
    }

    public function isGambar(): bool
    {
        return in_array($this->tipe_file, ['image/jpeg', 'image/png', 'image/jpg']);
    }
}
