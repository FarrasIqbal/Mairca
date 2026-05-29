<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * Test that guest accessing protected API endpoint gets 401 Unauthenticated.
     */
    public function test_guest_cannot_access_protected_endpoints(): void
    {
        $response = $this->getJson('/api/me');
        $response->assertStatus(401);
    }

    /**
     * Test login validation.
     */
    public function test_login_requires_email_and_password(): void
    {
        $response = $this->postJson('/api/login', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * Test login with incorrect credentials.
     */
    public function test_login_with_incorrect_credentials_fails(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(421);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Kredensial login tidak cocok.'
        ]);
    }

    /**
     * Test public test token route behaves correctly with invalid token.
     */
    public function test_public_test_with_invalid_token_returns_404(): void
    {
        $response = $this->getJson('/api/public/test/invalidtoken123');
        $response->assertStatus(404);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Token ujian tidak valid.'
        ]);
    }
}
