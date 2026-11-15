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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('pesanan_sewa');
        Schema::dropIfExists('tarif_sewa');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('pengguna');
        Schema::dropIfExists('admin');

        Schema::enableForeignKeyConstraints();

        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('foto')->nullable();
            $table->unsignedInteger('harga');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('stok')->default(0);
            $table->timestamps();

            $table->index('nama');
        });

        Schema::create('tarif_sewa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_barang')->constrained('barang')->cascadeOnDelete();
            $table->unsignedInteger('harga_per_jam');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->index(['id_barang', 'tanggal_mulai']);
        });

        Schema::create('pesanan_sewa', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan', 30)->unique();
            $table->foreignId('id_penyewa')->constrained('pengguna')->restrictOnDelete();
            $table->foreignId('id_barang')->constrained('barang')->restrictOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedInteger('durasi_hari');
            $table->unsignedInteger('total_harga');
            $table->string('status', 20)->default('menunggu');
            $table->text('catatan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('pesanan_sewa');
        Schema::dropIfExists('tarif_sewa');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('pengguna');
        Schema::dropIfExists('admin');

        Schema::enableForeignKeyConstraints();
    }
};
