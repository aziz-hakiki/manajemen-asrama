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

class BookingDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_resepsionis_can_view_aksi_column_in_daftar_reservasi_dan_hunian(): void
    {
        $user = User::factory()->create([
            'role' => 'resepsionis',
        ]);

        $gedung = Gedung::create(['nama_gedung' => 'Asrama A']);
        $kamar = Kamar::create([
            'gedung_id' => $gedung->id,
            'nomor_kamar' => '101',
            'kapasitas' => 2,
            'status' => 'kosong',
        ]);

        $booking = Booking::create([
            'kamar_id' => $kamar->id,
            'nama_pemesan' => 'Budi Santoso',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(2)->toDateString(),
            'status' => 'booked',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('resepsionis.booking.index', [
            'gedung_id' => $gedung->id,
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
        $response->assertSee('Aksi');
        $response->assertSee('Hapus');
        $response->assertSee(route('resepsionis.booking.destroy', $booking->id));
    }

    public function test_resepsionis_can_delete_booking(): void
    {
        $user = User::factory()->create([
            'role' => 'resepsionis',
        ]);

        $gedung = Gedung::create(['nama_gedung' => 'Asrama A']);
        $kamar = Kamar::create([
            'gedung_id' => $gedung->id,
            'nomor_kamar' => '101',
            'kapasitas' => 2,
            'status' => 'kosong',
        ]);

        $booking = Booking::create([
            'kamar_id' => $kamar->id,
            'nama_pemesan' => 'Budi Santoso',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(2)->toDateString(),
            'status' => 'booked',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('resepsionis.booking.destroy', $booking));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    public function test_resepsionis_can_delete_transaksi_hunian(): void
    {
        $user = User::factory()->create([
            'role' => 'resepsionis',
        ]);

        $gedung = Gedung::create(['nama_gedung' => 'Asrama A']);
        $kamar = Kamar::create([
            'gedung_id' => $gedung->id,
            'nomor_kamar' => '102',
            'kapasitas' => 1,
            'status' => 'terisi',
        ]);

        $diklat = Diklat::create([
            'nama_diklat' => 'Diklat Teknis',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(5)->toDateString(),
        ]);

        $peserta = Peserta::create([
            'diklat_id' => $diklat->id,
            'nama_peserta' => 'Ahmad Dahlan',
            'nip_nik' => '198501012010011001',
        ]);

        $transaksi = TransaksiAsrama::create([
            'peserta_id' => $peserta->id,
            'kamar_id' => $kamar->id,
            'tanggal_masuk' => now(),
            'status' => 'menginap',
        ]);

        $response = $this->actingAs($user)->delete(route('resepsionis.booking.transaksi.destroy', $transaksi));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('transaksi_asramas', [
            'id' => $transaksi->id,
        ]);

        // Status kamar harus kembali kosong karena tidak ada lagi penghuni
        $this->assertDatabaseHas('kamars', [
            'id' => $kamar->id,
            'status' => 'kosong',
        ]);
    }
}
