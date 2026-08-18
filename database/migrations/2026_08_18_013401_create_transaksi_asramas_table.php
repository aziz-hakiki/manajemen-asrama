<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_asramas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('pesertas')->cascadeOnDelete();
            $table->foreignId('kamar_id')->constrained('kamars')->cascadeOnDelete();
            $table->dateTime('tanggal_masuk');
            $table->dateTime('tanggal_keluar')->nullable();
            $table->enum('status', ['menginap', 'selesai'])->default('menginap');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_asramas');
    }
};
