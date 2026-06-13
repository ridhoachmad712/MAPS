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
        'sumber',
        'url',
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

    public function isTautan(): bool
    {
        return $this->sumber === 'tautan';
    }

    public function isGambar(): bool
    {
        return $this->sumber === 'berkas'
            && in_array($this->tipe_file, ['image/jpeg', 'image/png', 'image/jpg']);
    }

    /**
     * Alamat untuk dibuka: URL langsung (tautan) atau route berkas (unggahan).
     */
    public function alamat(): string
    {
        return $this->isTautan() ? (string) $this->url : route('bukti.show', $this);
    }

    /**
     * Layanan tautan untuk pelabelan (Google Drive, YouTube, dll.).
     */
    public function layanan(): string
    {
        $host = strtolower((string) parse_url((string) $this->url, PHP_URL_HOST));

        return match (true) {
            str_contains($host, 'drive.google') || str_contains($host, 'docs.google') => 'Google Drive',
            str_contains($host, 'youtu') => 'YouTube',
            str_contains($host, 'onedrive') || str_contains($host, '1drv.ms') => 'OneDrive',
            str_contains($host, 'dropbox') => 'Dropbox',
            $host !== '' => preg_replace('/^www\./', '', $host),
            default => 'Tautan',
        };
    }

    /**
     * URL sematan untuk pratinjau (khusus Google Drive); null jika tak didukung.
     */
    public function urlSematan(): ?string
    {
        if (! $this->isTautan()) {
            return null;
        }

        if (preg_match('~drive\.google\.com/file/d/([^/]+)~', (string) $this->url, $m)) {
            return 'https://drive.google.com/file/d/'.$m[1].'/preview';
        }

        return null;
    }
}
