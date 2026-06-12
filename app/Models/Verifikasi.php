<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verifikasi extends Model
{
    protected $table = 'verifikasi';

    protected $primaryKey = 'verifikasi_id';

    public $timestamps = false;

    public const HASIL_LABEL = [
        'diverifikasi' => 'Diverifikasi',
        'ditolak' => 'Ditolak',
        'revisi' => 'Perlu Revisi',
    ];

    public const HASIL_BADGE = [
        'diverifikasi' => 'success',
        'ditolak' => 'danger',
        'revisi' => 'info',
    ];

    protected $fillable = [
        'portofolio_id',
        'verifikator_id',
        'hasil',
        'catatan',
        'tanggal_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_verifikasi' => 'datetime',
        ];
    }

    public function portofolio(): BelongsTo
    {
        return $this->belongsTo(Portofolio::class, 'portofolio_id', 'portofolio_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikator_id', 'user_id');
    }

    public function hasilLabel(): string
    {
        return self::HASIL_LABEL[$this->hasil] ?? $this->hasil;
    }

    public function hasilBadge(): string
    {
        return self::HASIL_BADGE[$this->hasil] ?? 'secondary';
    }
}
