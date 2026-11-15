<?php

namespace App\Models;

use Database\Factories\PesananSewaFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananSewa extends Model
{
    /** @use HasFactory<PesananSewaFactory> */
    use HasFactory;

    public const STATUS_MENUNGGU = 'menunggu';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DISEWA = 'disewa';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    /**
     * @var array<string, string>
     */
    public const STATUS_LABELS = [
        self::STATUS_MENUNGGU => 'Menunggu Konfirmasi',
        self::STATUS_DISETUJUI => 'Disetujui',
        self::STATUS_DISEWA => 'Sedang Disewa',
        self::STATUS_SELESAI => 'Selesai',
        self::STATUS_DITOLAK => 'Ditolak',
        self::STATUS_DIBATALKAN => 'Dibatalkan',
    ];

    protected $table = 'pesanan_sewa';

    protected $fillable = [
        'nomor_pesanan',
        'id_penyewa',
        'id_barang',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi_hari',
        'total_harga',
        'status',
        'catatan',
        'alasan_penolakan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'durasi_hari' => 'integer',
            'total_harga' => 'integer',
        ];
    }

    public function penyewa(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_penyewa');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function labelStatus(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function bisaDibatalkan(): bool
    {
        return $this->status === self::STATUS_MENUNGGU;
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $query->when(filled($status), fn (Builder $query): Builder => $query->where('status', $status));
    }

    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SELESAI);
    }
}
