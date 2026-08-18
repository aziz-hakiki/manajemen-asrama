<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peserta extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function diklat(): BelongsTo
    {
        return $this->belongsTo(Diklat::class);
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(TransaksiAsrama::class);
    }
}
