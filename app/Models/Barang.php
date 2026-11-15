<?php

namespace App\Models;

use Database\Factories\BarangFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Barang extends Model
{
    /** @use HasFactory<BarangFactory> */
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama',
        'foto',
        'harga',
        'deskripsi',
        'stok',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'stok' => 'integer',
        ];
    }

    public function tarifSewa(): HasMany
    {
        return $this->hasMany(TarifSewa::class, 'id_barang');
    }

    public function tarifTerbaru(): HasOne
    {
        return $this->hasOne(TarifSewa::class, 'id_barang')->latestOfMany('tanggal_mulai');
    }

    public function pesananSewa(): HasMany
    {
        return $this->hasMany(PesananSewa::class, 'id_barang');
    }

    protected function fotoUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->foto
            ? asset('foto_barang/'.$this->foto)
            : asset('foto_barang/placeholder.svg'));
    }

    public function scopeCari(Builder $query, ?string $kataKunci): Builder
    {
        return $query->when(
            filled($kataKunci),
            fn (Builder $query): Builder => $query->where(function (Builder $query) use ($kataKunci): void {
                $query->where('nama', 'like', '%'.$kataKunci.'%')
                    ->orWhere('deskripsi', 'like', '%'.$kataKunci.'%');
            })
        );
    }

    public function scopeTersedia(Builder $query): Builder
    {
        return $query->where('stok', '>', 0);
    }
}
