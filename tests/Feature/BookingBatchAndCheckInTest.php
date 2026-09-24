<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Diklat;
use App\Models\Gedung;
use App\Models\Kamar;
use App\Models\Peserta;
use App\Models\TransaksiAsrama;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingBatchAndCheckInTest extends TestCase
{
    use RefreshDatabase;

    public function test_resepsionis_can_batch_book_asrama_for_diklat(): void
    {
        $user = User::factory()->create([
            'role' => 'resepsionis',
        ]);

        $gedung = Gedung::create(['nama_gedung' => 'Asrama A']);
        $kamar1 = Kamar::create(['gedung_id' => $gedung->id, 'nomor_kamar' => '101', 'kapasitas' => 2, 'status' => 'kosong']);
        $kamar2 = Kamar::create(['gedung_id' => $gedung->id, 'nomor_kamar' => '102', 'kapasitas' => 2, 'status' => 'kosong']);

        $diklat = Diklat::create([
            'nama_diklat' => 'Pelatihan Kepemimpinan Administrator',
            'tanggal_mulai' => '2026-10-07',
            'tanggal_selesai' => '2026-10-12',
        ]);

        $response = $this->actingAs($user)->post(route('resepsionis.booking.batch'), [
            'diklat_id' => $diklat->id,
            'gedung_id' => $gedung->id,
            'tanggal_mulai' => '2026-10-07',
            'tanggal_selesai' => '2026-10-12',
            'mode_kamar' => 'semua',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('resepsionis.booking.index', [
            'gedung_id' => $gedung->id,
            'tanggal' => '2026-10-07',
        ]));

        // Kamar 101 dan Kamar 102 harus memiliki booking aktif pada tanggal tersebut
        $this->assertDatabaseHas('bookings', [
            'kamar_id' => $kamar1->id,
            'diklat_id' => $diklat->id,
            'status' => 'booked',
        ]);

        $this->assertDatabaseHas('bookings', [
            'kamar_id' => $kamar2->id,
            'diklat_id' => $diklat->id,
            'status' => 'booked',
        ]);

        // Cek halaman pada 07 Oktober 2026: harus berstatus Booked
        $viewResponse = $this->actingAs($user)->get(route('resepsionis.booking.index', [
            'gedung_id' => $gedung->id,
            'tanggal' => '2026-10-07',
        ]));

        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Pelatihan Kepemimpinan Administrator');
        $viewResponse->assertSee('Booked');
    }

    public function test_checkin_changes_room_to_red_terisi_and_shows_occupant_data(): void
    {
        $user = User::factory()->create([
            'role' => 'resepsionis',
        ]);

        $gedung = Gedung::create(['nama_gedung' => 'Asrama A']);
        $kamar1 = Kamar::create(['gedung_id' => $gedung->id, 'nomor_kamar' => '101', 'kapasitas' => 2, 'status' => 'kosong']);
        $kamar2 = Kamar::create(['gedung_id' => $gedung->id, 'nomor_kamar' => '102', 'kapasitas' => 2, 'status' => 'kosong']);

        $diklat = Diklat::create([
            'nama_diklat' => 'Diklat Teknis Kehumasan',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
        ]);

        $peserta1 = Peserta::create([
            'diklat_id' => $diklat->id,
            'nama_peserta' => 'Budi Santoso',
            'nip_nik' => '198501012010011001',
            'instansi' => 'Kementerian Keuangan',
        ]);

        $peserta2 = Peserta::create([
            'diklat_id' => $diklat->id,
            'nama_peserta' => 'Dewi Lestari',
            'nip_nik' => '199002022015022002',
            'instansi' => 'Bappenas',
        ]);

        // Buat booking batch untuk kamar 101 dan 102
        $booking1 = Booking::create([
            'kamar_id' => $kamar1->id,
            'diklat_id' => $diklat->id,
            'nama_pemesan' => $diklat->nama_diklat,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status' => 'booked',
            'user_id' => $user->id,
        ]);

        $booking2 = Booking::create([
            'kamar_id' => $kamar2->id,
            'diklat_id' => $diklat->id,
            'nama_pemesan' => $diklat->nama_diklat,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
            'status' => 'booked',
            'user_id' => $user->id,
        ]);

        // Check-in peserta 1 ke Kamar 101 via modal checkin endpoint
        $checkinResponse = $this->actingAs($user)->post(route('resepsionis.booking.checkin', $booking1), [
            'peserta_id' => $peserta1->id,
        ]);

        $checkinResponse->assertStatus(302);

        // Kamar 101 harus terisi
        $this->assertDatabaseHas('transaksi_asramas', [
            'kamar_id' => $kamar1->id,
            'peserta_id' => $peserta1->id,
            'status' => 'menginap',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking1->id,
            'status' => 'checkin',
            'peserta_id' => $peserta1->id,
        ]);

        // Kamar 102 masih berstatus 'booked'
        $this->assertDatabaseHas('bookings', [
            'id' => $booking2->id,
            'status' => 'booked',
        ]);

        // Akses halaman denah bioskop pada hari ini
        $viewResponse = $this->actingAs($user)->get(route('resepsionis.booking.index', [
            'gedung_id' => $gedung->id,
            'tanggal' => now()->toDateString(),
        ]));

        $viewResponse->assertStatus(200);

        // Kamar 101 berstatus Terisi dan menampilkan nama Budi Santoso
        $viewResponse->assertSee('Budi Santoso');
        $viewResponse->assertSee('Terisi');

        // Kamar 102 masih berstatus Booked
        $viewResponse->assertSee('Booked');
    }

    public function test_booking_index_only_displays_upcoming_diklats_in_selection(): void
    {
        $user = User::factory()->create([
            'role' => 'resepsionis',
        ]);

        $gedung = Gedung::create(['nama_gedung' => 'Asrama B']);
        Kamar::create(['gedung_id' => $gedung->id, 'nomor_kamar' => '201', 'kapasitas' => 2, 'status' => 'kosong']);

        $pastDiklat = Diklat::create([
            'nama_diklat' => 'Diklat Sudah Lewat',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->subDays(1)->toDateString(),
        ]);

        $upcomingDiklat = Diklat::create([
            'nama_diklat' => 'Diklat Masa Depan',
            'tanggal_mulai' => now()->addDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(10)->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('resepsionis.booking.index', [
            'gedung_id' => $gedung->id,
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertStatus(200);

        $diklatsInView = $response->viewData('diklats');
        $this->assertFalse($diklatsInView->contains('id', $pastDiklat->id));
        $this->assertTrue($diklatsInView->contains('id', $upcomingDiklat->id));

        $diklatsJsonInView = $response->viewData('diklatsJson');
        $this->assertFalse($diklatsJsonInView->contains('id', $pastDiklat->id));
        $this->assertTrue($diklatsJsonInView->contains('id', $upcomingDiklat->id));
    }

    public function test_booking_index_filters_gedung_options_for_specific_asrama_receptionist(): void
    {
        $gedungA = Gedung::create(['nama_gedung' => 'Asrama A']);
        $gedungB = Gedung::create(['nama_gedung' => 'Asrama B']);
        $gedungC = Gedung::create(['nama_gedung' => 'Asrama C']);

        Kamar::create(['gedung_id' => $gedungA->id, 'nomor_kamar' => 'A101', 'kapasitas' => 2, 'status' => 'kosong']);
        Kamar::create(['gedung_id' => $gedungB->id, 'nomor_kamar' => 'B101', 'kapasitas' => 2, 'status' => 'kosong']);
        Kamar::create(['gedung_id' => $gedungC->id, 'nomor_kamar' => 'C101', 'kapasitas' => 2, 'status' => 'kosong']);

        // Resepsionis bertugas di Asrama A
        $userAsramaA = User::factory()->create([
            'role' => 'resepsionis',
            'gedung_id' => $gedungA->id,
        ]);

        $response = $this->actingAs($userAsramaA)->get(route('resepsionis.booking.index', [
            'gedung_id' => $gedungA->id,
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertStatus(200);

        $gedungsJson = $response->viewData('gedungsJson');
        $this->assertCount(1, $gedungsJson);
        $this->assertEquals($gedungA->id, $gedungsJson[0]['id']);
        $this->assertEquals('Asrama A', $gedungsJson[0]['nama_gedung']);
    }

    public function test_booking_index_shows_all_gedungs_for_semua_asrama_receptionist(): void
    {
        $gedungA = Gedung::create(['nama_gedung' => 'Asrama A']);
        $gedungB = Gedung::create(['nama_gedung' => 'Asrama B']);
        $gedungC = Gedung::create(['nama_gedung' => 'Asrama C']);

        Kamar::create(['gedung_id' => $gedungA->id, 'nomor_kamar' => 'A101', 'kapasitas' => 2, 'status' => 'kosong']);
        Kamar::create(['gedung_id' => $gedungB->id, 'nomor_kamar' => 'B101', 'kapasitas' => 2, 'status' => 'kosong']);
        Kamar::create(['gedung_id' => $gedungC->id, 'nomor_kamar' => 'C101', 'kapasitas' => 2, 'status' => 'kosong']);

        // Resepsionis dengan penugasan Semua Asrama (gedung_id = null)
        $userSemuaAsrama = User::factory()->create([
            'role' => 'resepsionis',
            'gedung_id' => null,
        ]);

        $response = $this->actingAs($userSemuaAsrama)->get(route('resepsionis.booking.index', [
            'gedung_id' => $gedungA->id,
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertStatus(200);

        $gedungsJson = $response->viewData('gedungsJson');
        $this->assertCount(3, $gedungsJson);
        $gedungIds = collect($gedungsJson)->pluck('id');
        $this->assertTrue($gedungIds->contains($gedungA->id));
        $this->assertTrue($gedungIds->contains($gedungB->id));
        $this->assertTrue($gedungIds->contains($gedungC->id));
    }
}


