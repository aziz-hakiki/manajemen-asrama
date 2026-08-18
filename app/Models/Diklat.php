<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diklat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pesertas(): HasMany
    {
        return $this->hasMany(Peserta::class);
    }
}
