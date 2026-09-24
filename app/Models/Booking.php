<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class);
    }

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }

    public function diklat(): BelongsTo
    {
        return $this->belongsTo(Diklat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeOnDate($query, $date)
    {
        return $query->where('status', 'booked')
            ->whereDate('tanggal_mulai', '<=', $date)
            ->whereDate('tanggal_selesai', '>=', $date);
    }

    public function scopeOverlapping($query, $start, $end)
    {
        return $query->where('status', 'booked')
            ->whereDate('tanggal_mulai', '<=', $end)
            ->whereDate('tanggal_selesai', '>=', $start);
    }
}
