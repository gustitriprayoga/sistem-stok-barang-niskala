<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Barang extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'satuan',
        'stok_maksimal',
        // =========================================================
        // PASTIKAN 'stok_sekarang' ADA DI DALAM ARRAY INI
        // =========================================================
        'stok_sekarang',
    ];

    /**
     * Accessor untuk menghitung persentase sisa stok.
     */
    protected function persentaseStok(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->stok_maksimal > 0) {
                    return ($this->stok_sekarang / $this->stok_maksimal) * 100;
                }
                return 0;
            },
        );
    }
}
