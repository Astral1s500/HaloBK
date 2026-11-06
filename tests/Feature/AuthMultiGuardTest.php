<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\GuruBk;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthMultiGuardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Siapkan data pengujian
        $admin = Admin::create([
            'nama_admin' => 'Admin Penguji',
            'username'   => 'admin',
            'password'   => Hash::make('password'),
        ]);

        $guru = GuruBk::create([
            'nama_guru' => 'Guru Penguji',
            'nip'       => '198501012010011001',
            'password'  => Hash::make('password'),
        ]);

        $kelas = Kelas::create([
            'id_guru'         => $guru->id_guru,
            'nama_kelas'      => 'XII-IPA-1',
            'kode_unik_kelas' => 'BK-TEST1',
        ]);

        Siswa::create([
            'id_kelas'   => $kelas->id_kelas,
            'id_admin'   => $admin->id_admin,
            'nama_siswa' => 'Siswa Penguji',
            'nisn'       => '0051234567',
            'password'   => Hash::make('password'),
        ]);
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('HaloBK');
        $response->assertSee('Username / NIP / NISN');
    }

    public function test_admin_can_login_with_username_and_access_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'identifier' => 'admin',
            'password'   => 'password',
            'remember'   => '1',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertFalse(Auth::guard('guru')->check());
        $this->assertFalse(Auth::guard('siswa')->check());

        $dashboardResponse = $this->get('/admin/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard Administrator');
    }

    public function test_guru_can_login_with_nip_and_access_guru_dashboard(): void
    {
        $response = $this->post('/login', [
            'identifier' => '198501012010011001',
            'password'   => 'password',
            'remember'   => '1',
        ]);

        $response->assertRedirect(route('guru.dashboard'));
        $this->assertTrue(Auth::guard('guru')->check());
        $this->assertFalse(Auth::guard('admin')->check());
        $this->assertFalse(Auth::guard('siswa')->check());

        $dashboardResponse = $this->get('/guru/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard Guru BK');
    }

    public function test_siswa_can_login_with_nisn_and_access_siswa_dashboard(): void
    {
        $response = $this->post('/login', [
            'identifier' => '0051234567',
            'password'   => 'password',
            'remember'   => '1',
        ]);

        $response->assertRedirect(route('siswa.dashboard'));
        $this->assertTrue(Auth::guard('siswa')->check());
        $this->assertFalse(Auth::guard('admin')->check());
        $this->assertFalse(Auth::guard('guru')->check());

        $dashboardResponse = $this->get('/siswa/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard Siswa');
    }

    public function test_invalid_credentials_fail_and_stay_on_login(): void
    {
        $response = $this->from('/login')->post('/login', [
            'identifier' => 'salah_identitas',
            'password'   => 'password_salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('identifier');
        $this->assertFalse(Auth::guard('admin')->check());
        $this->assertFalse(Auth::guard('guru')->check());
        $this->assertFalse(Auth::guard('siswa')->check());
    }

    public function test_unauthenticated_user_cannot_access_protected_dashboards(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
        $this->get('/guru/dashboard')->assertRedirect(route('login'));
        $this->get('/siswa/dashboard')->assertRedirect(route('login'));
    }

    public function test_user_can_logout(): void
    {
        $this->post('/login', [
            'identifier' => 'admin',
            'password'   => 'password',
        ]);
        $this->assertTrue(Auth::guard('admin')->check());

        $logoutResponse = $this->post('/logout');
        $logoutResponse->assertRedirect(route('login'));
        $this->assertFalse(Auth::guard('admin')->check());
    }
}
