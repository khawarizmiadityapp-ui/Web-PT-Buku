<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MfaAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_valid_login_credentials_redirect_to_mfa_page(): void
    {
        $user = User::create([
            'name' => 'MFA Test User',
            'email' => 'mfatest@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'System Admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'mfatest@example.com',
            'password' => 'SecretPass123!',
        ]);

        $response->assertRedirect(route('login.mfa'));
        $this->assertFalse(Auth::check(), 'User should not be fully authenticated before MFA verification');
        $this->assertEquals($user->id, session('auth.mfa_user_id'));
    }

    public function test_mfa_page_cannot_be_accessed_without_valid_mfa_session(): void
    {
        $response = $this->get('/login/mfa');
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_mfa_verification_succeeds_with_static_code(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin_mfa@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'System Admin',
        ]);

        $this->withSession([
            'auth.mfa_user_id' => $user->id,
            'auth.mfa_remember' => false,
            'auth.mfa_expires_at' => now()->addMinutes(15)->timestamp,
        ]);

        $response = $this->post('/login/mfa', [
            'code' => '123456',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
        $this->assertTrue(session('mfa_verified'));
        $this->assertNull(session('auth.mfa_user_id'));
    }

    public function test_mfa_verification_fails_with_wrong_code(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin_mfa2@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'System Admin',
        ]);

        $this->withSession([
            'auth.mfa_user_id' => $user->id,
            'auth.mfa_remember' => false,
            'auth.mfa_expires_at' => now()->addMinutes(15)->timestamp,
        ]);

        $response = $this->post('/login/mfa', [
            'code' => '999999',
        ]);

        $response->assertSessionHasErrors('code');
        $this->assertFalse(Auth::check());
    }

    public function test_mfa_session_expires_after_15_minutes(): void
    {
        $user = User::create([
            'name' => 'Expired User',
            'email' => 'expired_mfa@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'System Admin',
        ]);

        // Simulated session that expired 1 second ago
        $this->withSession([
            'auth.mfa_user_id' => $user->id,
            'auth.mfa_remember' => false,
            'auth.mfa_expires_at' => now()->subSecond()->timestamp,
        ]);

        $response = $this->get('/login/mfa');
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_mfa_can_be_cancelled(): void
    {
        $user = User::create([
            'name' => 'Cancel User',
            'email' => 'cancel_mfa@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'System Admin',
        ]);

        $this->withSession([
            'auth.mfa_user_id' => $user->id,
            'auth.mfa_remember' => false,
            'auth.mfa_expires_at' => now()->addMinutes(15)->timestamp,
        ]);

        $response = $this->post('/login/mfa/cancel');
        $response->assertRedirect(route('login'));
        $this->assertNull(session('auth.mfa_user_id'));
    }

    public function test_cashier_redirected_to_cashier_after_mfa(): void
    {
        $cashier = User::create([
            'name' => 'Cashier User',
            'email' => 'cashier_mfa@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'Cashier',
        ]);

        $this->withSession([
            'auth.mfa_user_id' => $cashier->id,
            'auth.mfa_remember' => false,
            'auth.mfa_expires_at' => now()->addMinutes(15)->timestamp,
        ]);

        $response = $this->post('/login/mfa', [
            'code' => '123456',
        ]);

        $response->assertRedirect(route('cashier.index'));
        $this->assertTrue(Auth::check());
        $this->assertEquals('Cashier', Auth::user()->role);
    }
}
