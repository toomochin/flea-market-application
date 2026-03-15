<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ID 1: 会員登録機能のテスト
    public function test_registration_fails_without_name()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertInvalid(['name']);
    }

    public function test_registration_fails_without_email()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertInvalid(['email']);
    }

    public function test_registration_fails_without_password()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);
        $response->assertInvalid(['password']);
    }

    public function test_registration_fails_with_short_password()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);
        $response->assertInvalid(['password']);
    }

    public function test_registration_fails_if_passwords_do_not_match()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);
        $response->assertInvalid(['password']);
    }

    public function test_successful_registration_redirects_to_profile_setup()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertAuthenticated();
        $response->assertRedirect('/mypage'); // ※実際のプロフィール設定画面のURLに合わせてください
    }

    // ID 2: ログイン機能のテスト
    public function test_login_fails_without_email()
    {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);
        $response->assertInvalid(['email']);
    }

    public function test_login_fails_without_password()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
        ]);
        $response->assertInvalid(['password']);
    }

    public function test_login_fails_with_incorrect_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
    }

    public function test_successful_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    // ID 3: ログアウト機能のテスト
    public function test_successful_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}