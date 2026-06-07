<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function login_form_submission_authenticates_valid_user(): void
    {
        $user = User::factory()->create([
            'email' => 'gm@goldenbird.co.id',
            'password' => Hash::make('password123'),
            'role' => 'gm',
        ]);

        $response = $this->withSession(['_token' => 'test-token'])->post('/login', [
            '_token' => 'test-token',
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function login_form_submission_rejects_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'gm@goldenbird.co.id',
            'password' => Hash::make('password123'),
            'role' => 'gm',
        ]);

        $response = $this->withSession(['_token' => 'test-token'])->from('/login')->post('/login', [
            '_token' => 'test-token',
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
