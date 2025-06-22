<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Barang extends Model
{
    use HasFactory;

    protected $guarded = [];

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
