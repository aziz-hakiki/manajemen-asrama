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

    public function activeTransaksi(): HasMany
    {
        return $this->hasMany(TransaksiAsrama::class)->where('status', 'menginap');
    }

    public function getTerisiCountAttribute(): int
    {
        if (isset($this->attributes['terisi_count'])) {
            return (int) $this->attributes['terisi_count'];
        }

        if ($this->relationLoaded('activeTransaksi')) {
            return $this->activeTransaksi->count();
        }

        if ($this->relationLoaded('transaksi')) {
            return $this->transaksi->where('status', 'menginap')->count();
        }

        return $this->activeTransaksi()->count();
    }

    public function getSisaSlotAttribute(): int
    {
        return max(0, $this->kapasitas - $this->terisi_count);
    }

    public function getStatusLabelAttribute(): string
    {
        $count = $this->terisi_count;
        if ($count === 0) {
            return 'Kosong';
        }
        if ($count === 1) {
            return '1 Terisi';
        }
        if ($count === 2) {
            return '2 Terisi';
        }
        return '3 Terisi';
    }

    public function isAvailable(): bool
    {
        return $this->terisi_count < $this->kapasitas;
    }

    public function isFull(): bool
    {
        return $this->terisi_count >= $this->kapasitas;
    }
}
