<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kamar extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function gedung(): BelongsTo
    {
        return $this->belongsTo(Gedung::class);
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(TransaksiAsrama::class);
    }
}
