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
     * Mendapatkan ID gedung yang ditugaskan ke user
     */
    public function assignedGedungId(): ?int
    {
        if ($this->gedung_id) {
            return (int) $this->gedung_id;
        }

        if ($this->role === 'resepsionis') {
            $identifier = strtolower($this->name . ' ' . $this->email);
            if (str_contains($identifier, 'asrama a') || str_contains($identifier, 'gedung a') || str_contains($identifier, 'resepsionis a') || str_contains($identifier, 'resepsionis_a')) {
                return Gedung::where('nama_gedung', 'like', '%Asrama A%')->orWhere('nama_gedung', 'like', '%A%')->value('id');
            }
            if (str_contains($identifier, 'asrama b') || str_contains($identifier, 'gedung b') || str_contains($identifier, 'resepsionis b') || str_contains($identifier, 'resepsionis_b')) {
                return Gedung::where('nama_gedung', 'like', '%Asrama B%')->orWhere('nama_gedung', 'like', '%B%')->value('id');
            }
            if (str_contains($identifier, 'asrama c') || str_contains($identifier, 'gedung c') || str_contains($identifier, 'resepsionis c') || str_contains($identifier, 'resepsionis_c')) {
                return Gedung::where('nama_gedung', 'like', '%Asrama C%')->orWhere('nama_gedung', 'like', '%C%')->value('id');
            }
            if (str_contains($identifier, 'asrama d') || str_contains($identifier, 'gedung d') || str_contains($identifier, 'resepsionis d') || str_contains($identifier, 'resepsionis_d')) {
                return Gedung::where('nama_gedung', 'like', '%Asrama D%')->orWhere('nama_gedung', 'like', '%D%')->value('id');
            }
        }

        return null;
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
