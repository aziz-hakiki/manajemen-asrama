<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PimpinanDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_pimpinan_dashboard_displays_correct_year_beyond_2030(): void
    {
        $user = User::factory()->create([
            'role' => 'pimpinan',
        ]);

        // Uji tahun 2035 (sebelumnya dibatasi hanya sampai 2030)
        $response = $this
            ->actingAs($user)
            ->get(route('pimpinan.dashboard', ['periode' => 2035]));

        $response->assertOk();
        $response->assertViewHas('selectedPeriode', '2035');
        $response->assertSee('Tren Okupansi Kamar Asrama (Januari - Desember) 2035');
        $response->assertSee('value="2035"', false);

        // Uji tahun sebelum 2026, misal 2024
        $responsePast = $this
            ->actingAs($user)
            ->get(route('pimpinan.dashboard', ['periode' => 2024]));

        $responsePast->assertOk();
        $responsePast->assertViewHas('selectedPeriode', '2024');
        $responsePast->assertSee('Tren Okupansi Kamar Asrama (Januari - Desember) 2024');
        $responsePast->assertSee('value="2024"', false);
    }
}
