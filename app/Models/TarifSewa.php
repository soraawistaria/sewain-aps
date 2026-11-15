<?php

namespace App\Models;

use Database\Factories\TarifSewaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TarifSewa extends Model
{
    /** @use HasFactory<TarifSewaFactory> */
    use HasFactory;

    protected $table = 'tarif_sewa';

    protected $fillable = [
        'id_barang',
        'harga_per_jam',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga_per_jam' => 'integer',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
