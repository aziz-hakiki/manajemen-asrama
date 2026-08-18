<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Resepsionis User',
            'email' => 'resepsionis@example.com',
            'password' => bcrypt('password'),
            'role' => 'resepsionis',
        ]);

        User::factory()->create([
            'name' => 'Pimpinan User',
            'email' => 'pimpinan@example.com',
            'password' => bcrypt('password'),
            'role' => 'pimpinan',
        ]);

        $gedungA = \App\Models\Gedung::create(['nama_gedung' => 'Gedung A']);
        $gedungB = \App\Models\Gedung::create(['nama_gedung' => 'Gedung B']);

        for ($i = 101; $i <= 105; $i++) {
            \App\Models\Kamar::create([
                'gedung_id' => $gedungA->id,
                'nomor_kamar' => 'A' . $i,
                'kapasitas' => 2,
            ]);
        }

        for ($i = 201; $i <= 205; $i++) {
            \App\Models\Kamar::create([
                'gedung_id' => $gedungB->id,
                'nomor_kamar' => 'B' . $i,
                'kapasitas' => 2,
            ]);
        }
    }
}
