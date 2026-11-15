<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Barang;
use App\Models\Pengguna;
use App\Models\PesananSewa;
use App\Models\TarifSewa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SewainFlowTest extends TestCase
{
    use RefreshDatabase;

    private function buatPesanan(Barang $barang, Pengguna $pengguna, string $status = PesananSewa::STATUS_MENUNGGU): PesananSewa
    {
        return PesananSewa::factory()->create([
            'id_penyewa' => $pengguna->id,
            'id_barang' => $barang->id,
            'status' => $status,
            'durasi_hari' => 2,
            'total_harga' => $barang->harga * 2,
        ]);
    }

    public function test_halaman_publik_dapat_diakses(): void
    {
        $barang = Barang::factory()->create();

        $this->get(route('home'))->assertOk()->assertSee('Sewa kamera');
        $this->get(route('katalog.index'))->assertOk();
        $this->get(route('katalog.show', $barang))->assertOk()->assertSee($barang->nama);
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
        $this->get(route('admin.login'))->assertOk();
    }

    public function test_penyewa_dapat_mendaftar_dan_masuk(): void
    {
        $this->post(route('register.store'), [
            'username' => 'penyewa_baru',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('dashboard'));

        $pengguna = Pengguna::query()->where('username', 'penyewa_baru')->firstOrFail();

        $this->assertAuthenticatedAs($pengguna, 'web');

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest('web');

        $this->post(route('login.store'), [
            'username' => 'penyewa_baru',
            'password' => 'rahasia123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($pengguna, 'web');
    }

    public function test_penyewa_dapat_membuat_dan_membatalkan_pesanan(): void
    {
        $pengguna = Pengguna::factory()->create();
        $barang = Barang::factory()->create(['harga' => 100_000, 'stok' => 2]);

        $this->actingAs($pengguna, 'web')
            ->post(route('pesanan.store', $barang), [
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addDays(2)->toDateString(),
                'catatan' => 'Butuh tripod.',
            ])
            ->assertRedirect();

        $pesanan = PesananSewa::query()->firstOrFail();

        $this->assertSame($pengguna->id, $pesanan->id_penyewa);
        $this->assertSame(3, $pesanan->durasi_hari);
        $this->assertSame(300_000, $pesanan->total_harga);
        $this->assertSame(PesananSewa::STATUS_MENUNGGU, $pesanan->status);
        $this->assertSame(1, $barang->fresh()->stok);

        $this->actingAs($pengguna, 'web')
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee($pesanan->nomor_pesanan);

        $this->actingAs($pengguna, 'web')
            ->get(route('pesanan.show', $pesanan))
            ->assertOk()
            ->assertSee('Butuh tripod.');

        $this->actingAs($pengguna, 'web')
            ->patch(route('pesanan.cancel', $pesanan))
            ->assertRedirect();

        $this->assertSame(PesananSewa::STATUS_DIBATALKAN, $pesanan->fresh()->status);
        $this->assertSame(2, $barang->fresh()->stok);
    }

    public function test_penyewa_tidak_boleh_melihat_pesanan_milik_orang_lain(): void
    {
        $pengguna = Pengguna::factory()->create();
        $pesanan = $this->buatPesanan(Barang::factory()->create(), Pengguna::factory()->create());

        $this->actingAs($pengguna, 'web')
            ->get(route('pesanan.show', $pesanan))
            ->assertForbidden();
    }

    public function test_semua_halaman_penyewa_dapat_dirender(): void
    {
        $pengguna = Pengguna::factory()->create();
        $barang = Barang::factory()->create();
        $pesanan = $this->buatPesanan($barang, $pengguna);

        $this->actingAs($pengguna, 'web');

        $this->get(route('dashboard'))->assertOk();
        $this->get(route('pesanan.show', $pesanan))->assertOk();
        $this->get(route('profil.edit'))->assertOk();
        $this->get(route('katalog.show', $barang))->assertOk();
    }

    public function test_admin_dapat_melihat_semua_halaman_panel(): void
    {
        $admin = Admin::factory()->create();
        $pengguna = Pengguna::factory()->create();
        $barang = Barang::factory()->create();
        $pesanan = $this->buatPesanan($barang, $pengguna);

        $this->actingAs($admin, 'admin');

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.barang.index'))->assertOk();
        $this->get(route('admin.barang.create'))->assertOk();
        $this->get(route('admin.barang.edit', $barang))->assertOk();
        $this->get(route('admin.pesanan.index'))->assertOk();
        $this->get(route('admin.pesanan.show', $pesanan))->assertOk();
        $this->get(route('admin.pengguna.index'))->assertOk();
        $this->get(route('admin.laporan.index'))->assertOk();
        $this->get(route('admin.pengaturan.edit'))->assertOk();
    }

    public function test_admin_dapat_menambah_dan_mengubah_barang(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.barang.store'), [
                'nama' => 'Kamera Uji',
                'harga' => 120_000,
                'stok' => 3,
                'deskripsi' => 'Kamera untuk pengujian.',
            ])
            ->assertRedirect(route('admin.barang.index'));

        $barang = Barang::query()->where('nama', 'Kamera Uji')->firstOrFail();

        $this->assertSame(3, $barang->stok);
        $this->assertDatabaseHas('tarif_sewa', ['id_barang' => $barang->id]);
        $this->assertSame(5_000, TarifSewa::query()->where('id_barang', $barang->id)->value('harga_per_jam'));

        $this->actingAs($admin, 'admin')
            ->put(route('admin.barang.update', $barang), [
                'nama' => 'Kamera Uji Diperbarui',
                'harga' => 150_000,
                'stok' => 2,
                'deskripsi' => 'Deskripsi baru.',
            ])
            ->assertRedirect(route('admin.barang.index'));

        $this->assertDatabaseHas('barang', [
            'id' => $barang->id,
            'nama' => 'Kamera Uji Diperbarui',
            'harga' => 150_000,
            'stok' => 2,
        ]);

        $this->assertSame(6_300, TarifSewa::query()->where('id_barang', $barang->id)->value('harga_per_jam'));
    }

    public function test_admin_dapat_menyetujui_dan_menyelesaikan_pesanan(): void
    {
        $admin = Admin::factory()->create();
        $pengguna = Pengguna::factory()->create();
        $barang = Barang::factory()->create(['stok' => 1]);
        $pesanan = $this->buatPesanan($barang, $pengguna);

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.pesanan.approve', $pesanan))
            ->assertRedirect();

        $this->assertSame(PesananSewa::STATUS_DISETUJUI, $pesanan->fresh()->status);

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.pesanan.mark-rented', $pesanan))
            ->assertRedirect();

        $this->assertSame(PesananSewa::STATUS_DISEWA, $pesanan->fresh()->status);

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.pesanan.complete', $pesanan))
            ->assertRedirect();

        $this->assertSame(PesananSewa::STATUS_SELESAI, $pesanan->fresh()->status);
        $this->assertSame(2, $barang->fresh()->stok);
    }

    public function test_admin_dapat_menolak_pesanan_dan_mengembalikan_stok(): void
    {
        $admin = Admin::factory()->create();
        $barang = Barang::factory()->create(['stok' => 1]);
        $pesanan = $this->buatPesanan($barang, Pengguna::factory()->create());

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.pesanan.reject', $pesanan), [
                'alasan_penolakan' => 'Barang sedang diperbaiki.',
            ])
            ->assertRedirect();

        $pesanan->refresh();

        $this->assertSame(PesananSewa::STATUS_DITOLAK, $pesanan->status);
        $this->assertSame('Barang sedang diperbaiki.', $pesanan->alasan_penolakan);
        $this->assertSame(2, $barang->fresh()->stok);
    }

    public function test_admin_dapat_ubah_password(): void
    {
        $admin = Admin::factory()->create(['password' => 'password']);

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.pengaturan.password'), [
                'current_password' => 'password',
                'password' => 'passwordbaru',
                'password_confirmation' => 'passwordbaru',
            ])
            ->assertRedirect();

        $this->assertTrue(password_verify('passwordbaru', $admin->fresh()->password));
    }

    public function test_halaman_setup_tidak_tersedia_jika_admin_sudah_ada(): void
    {
        Admin::factory()->create();

        $this->get(route('admin.setup'))->assertNotFound();
    }

    public function test_admin_pertama_dapat_dibuat_melalui_halaman_setup(): void
    {
        $this->get(route('admin.setup'))->assertOk();

        $this->post(route('admin.setup.store'), [
            'username' => 'admin_baru',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('admin.login'));

        $this->assertDatabaseHas('admin', ['username' => 'admin_baru']);
    }

    public function test_tamu_diarahkan_ke_halaman_login_yang_sesuai(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_penyewa_tidak_dapat_mengakses_panel_admin(): void
    {
        $pengguna = Pengguna::factory()->create();

        $this->actingAs($pengguna, 'web')
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_pesanan_tidak_dapat_dibuat_jika_stok_habis(): void
    {
        $pengguna = Pengguna::factory()->create();
        $barang = Barang::factory()->create(['stok' => 0]);

        $this->actingAs($pengguna, 'web')
            ->post(route('pesanan.store', $barang), [
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addDay()->toDateString(),
            ])
            ->assertSessionHasErrors('tanggal_mulai');

        $this->assertDatabaseCount('pesanan_sewa', 0);
        $this->assertSame(0, $barang->fresh()->stok);
    }

    public function test_status_pesanan_tidak_dapat_dilompati(): void
    {
        $admin = Admin::factory()->create();
        $pesanan = $this->buatPesanan(Barang::factory()->create(), Pengguna::factory()->create());

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.pesanan.complete', $pesanan))
            ->assertSessionHasErrors('status');

        $this->assertSame(PesananSewa::STATUS_MENUNGGU, $pesanan->fresh()->status);
    }

    public function test_barang_dengan_riwayat_pesanan_tidak_dapat_dihapus(): void
    {
        $admin = Admin::factory()->create();
        $barang = Barang::factory()->create();
        $this->buatPesanan($barang, Pengguna::factory()->create());

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.barang.destroy', $barang))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('barang', ['id' => $barang->id]);
    }

    public function test_halaman_detail_barang_menampilkan_modal_pembayaran_qris(): void
    {
        $pengguna = Pengguna::factory()->create();
        $barang = Barang::factory()->create(['nama' => 'Kamera Modal', 'harga' => 100_000, 'stok' => 1]);

        $this->actingAs($pengguna, 'web')
            ->get(route('katalog.show', $barang))
            ->assertOk()
            ->assertSee('Sewa sekarang')
            ->assertSee('Sudah bayar')
            ->assertSee('Total pembayaran')
            ->assertSee('data-payment-modal', false)
            ->assertSee('images/qris-placeholder.svg', false);
    }

    public function test_pesanan_setelah_pembayaran_masuk_ke_dashboard_admin(): void
    {
        $pengguna = Pengguna::factory()->create();
        $barang = Barang::factory()->create(['harga' => 100_000, 'stok' => 1]);
        $admin = Admin::factory()->create();

        $this->actingAs($pengguna, 'web')
            ->post(route('pesanan.store', $barang), [
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addDay()->toDateString(),
            ])
            ->assertRedirect();

        $pesanan = PesananSewa::query()->firstOrFail();

        $this->assertSame(PesananSewa::STATUS_MENUNGGU, $pesanan->status);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee($pesanan->nomor_pesanan);
    }

    public function test_penyewa_dapat_mengubah_password(): void
    {
        $pengguna = Pengguna::factory()->create(['password' => 'password']);

        $this->actingAs($pengguna, 'web')
            ->patch(route('profil.update'), [
                'current_password' => 'password',
                'password' => 'passwordbaru',
                'password_confirmation' => 'passwordbaru',
            ])
            ->assertRedirect();

        $this->assertTrue(password_verify('passwordbaru', $pengguna->fresh()->password));
    }

    public function test_katalog_dapat_dicari_dan_disaring(): void
    {
        $tersedia = Barang::factory()->create(['nama' => 'Kamera Tersedia', 'stok' => 2]);
        $habis = Barang::factory()->create(['nama' => 'Kamera Habis', 'stok' => 0]);

        $this->get(route('katalog.index', ['q' => 'Tersedia', 'tersedia' => 1]))
            ->assertOk()
            ->assertSee($tersedia->nama)
            ->assertDontSee($habis->nama);
    }
}
