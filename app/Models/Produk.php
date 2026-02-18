<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;
    use HasFactory;

    public function produkbatches(): HasMany
    {
        return $this->hasMany(Produkbatches::class, 'produks_id');
    }

    public function racikanProduks(): HasMany
    {
        return $this->hasMany(Racikanproduk::class, 'produks_id');
    }
}
