<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test HTTP Security Headers are properly set and X-Powered-By is removed.
     */
    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $this->assertFalse($response->headers->has('X-Powered-By'), 'X-Powered-By should not be present');
    }

    /**
     * Test strong password policy enforcement.
     */
    public function test_password_policy_blocks_weak_and_accepts_strong(): void
    {
        $rules = [
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ];

        // 1. Too short
        $validatorShort = Validator::make(
            ['new_password' => '12345', 'new_password_confirmation' => '12345'],
            $rules
        );
        $this->assertTrue($validatorShort->fails());

        // 2. Only lowercase letters (no mixedCase, numbers, symbols)
        $validatorSimple = Validator::make(
            ['new_password' => 'secretpassword', 'new_password_confirmation' => 'secretpassword'],
            $rules
        );
        $this->assertTrue($validatorSimple->fails());

        // 3. Valid strong password
        $validatorStrong = Validator::make(
            ['new_password' => 'SecurePass#2026', 'new_password_confirmation' => 'SecurePass#2026'],
            $rules
        );
        $this->assertTrue($validatorStrong->passes());
    }

    /**
     * Test login rate limiter triggers after 5 failed attempts.
     */
    public function test_login_rate_limiter_throttling(): void
    {
        $email = 'bruteforcetest_' . uniqid() . '@example.com';

        // 5 failed login attempts
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->post('/login', [
                'email' => $email,
                'password' => 'WrongPassword123!',
            ]);
            $response->assertSessionHasErrors('email');
        }

        // 6th attempt should be blocked by rate limiter with friendly lockout message
        $response6 = $this->post('/login', [
            'email' => $email,
            'password' => 'WrongPassword123!',
        ]);

        $response6->assertSessionHasErrors(['email']);
        $errors = session('errors')->get('email');
        $this->assertStringContainsString('Terlalu banyak percobaan login gagal', $errors[0]);
        $this->assertStringContainsString('menit', $errors[0]);
    }
}
