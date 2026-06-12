<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portofolio extends Model
{
    protected $table = 'portofolio';

    protected $primaryKey = 'portofolio_id';

    public const STATUS_LABEL = [
        'draft' => 'Draft',
        'diajukan' => 'Diajukan',
        'diverifikasi' => 'Diverifikasi',
        'revisi' => 'Perlu Revisi',
        'ditolak' => 'Ditolak',
        'dipublikasikan' => 'Dipublikasikan',
    ];

    public const STATUS_BADGE = [
        'draft' => 'secondary',
        'diajukan' => 'warning',
        'diverifikasi' => 'success',
        'revisi' => 'info',
        'ditolak' => 'danger',
        'dipublikasikan' => 'primary',
    ];

    public const LEVEL_LABEL = [
        'regional' => 'Regional',
        'nasional' => 'Nasional',
        'internasional' => 'Internasional',
    ];

    protected $fillable = [
        'mahasiswa_id',
        'kategori_id',
        'judul',
        'deskripsi',
        'tahun_pencapaian',
        'level',
        'penyelenggara',
        'peran_capaian',
        'status',
        'is_publik',
    ];

    protected function casts(): array
    {
        return [
            'is_publik' => 'boolean',
            'tahun_pencapaian' => 'integer',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'kategori_id');
    }

    public function bukti(): HasMany
    {
        return $this->hasMany(Bukti::class, 'portofolio_id', 'portofolio_id');
    }

    public function verifikasi(): HasMany
    {
        return $this->hasMany(Verifikasi::class, 'portofolio_id', 'portofolio_id')
            ->orderByDesc('tanggal_verifikasi');
    }

    /**
     * Entri yang sudah lolos verifikasi (termasuk yang telah dipublikasikan).
     */
    public function scopeTerverifikasi(Builder $query): Builder
    {
        return $query->whereIn('status', ['diverifikasi', 'dipublikasikan']);
    }

    /**
     * Aturan tampil publik: terverifikasi DAN is_publik=true DAN konsen_publik mahasiswa=true.
     */
    public function scopePublik(Builder $query): Builder
    {
        return $query->terverifikasi()
            ->where('is_publik', true)
            ->whereHas('mahasiswa', fn (Builder $q) => $q->where('konsen_publik', true));
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABEL[$this->status] ?? $this->status;
    }

    public function statusBadge(): string
    {
        return self::STATUS_BADGE[$this->status] ?? 'secondary';
    }

    public function levelLabel(): string
    {
        return self::LEVEL_LABEL[$this->level] ?? $this->level;
    }

    public function bisaDieditMahasiswa(): bool
    {
        return in_array($this->status, ['draft', 'revisi', 'ditolak']);
    }

    public function bisaDiajukan(): bool
    {
        return in_array($this->status, ['draft', 'revisi', 'ditolak']);
    }
}
