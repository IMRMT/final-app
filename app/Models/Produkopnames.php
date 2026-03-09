<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produkopnames extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'produkopnames';
    protected $fillable = [
        'produks_id',
        'stok_sys',
        'stok_nyata',
        'satuans_id',
        'selisih',
        'tgl_opname',
        'deskripsi',
    ];

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuans_id');
    }

    public function produks(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produks_id');
    }
}
