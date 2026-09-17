<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gedung_id',
    ];

    /**
     * Relasi ke Gedung / Asrama penugasan
     */
    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'gedung_id');
    }

    /**
     * Mendapatkan ID gedung yang ditugaskan ke user.
     * Jika null, berarti resepsionis memiliki akses terbuka ke semua asrama.
     */
    public function assignedGedungId(): ?int
    {
        return $this->gedung_id ? (int) $this->gedung_id : null;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
