<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register',[
            "name" => "Eli",
            "email" => "e@mail.com",
            "password" => "123456789"  
        ]);
        
        $response->assertJsonFragment(["msg" => 'Gracias por registrarte, tu administrador tiene que validar tu registro para poder acceder a la aplicación']);
        $response->assertJsonFragment(["name" => "Eli"]);
        $this->assertCount(1, User::all());
    }

    public function test_user_not_register_cannot_login(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'e@mail.com',
            'password' => 987654321
        ]);

        $response->assertJsonFragment(["msg" => "No existe un usuario con ese mail, por favor regístrate"]);
    }

    public function test_user_cannot_login_without_validation(): void
    {
        $user = User::factory()->create([
            'email' => "e@mail.com",
            'password' => 123456789,
            'isValidated' => false
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $user->password
        ]);

        $response->assertJsonFragment(["msg" => "Tu usuario no está validado, contacta a tu administrador"]);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
            'isValidated' => true
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 123456789
        ]);

        $response->assertJsonFragment(["msg" => ["Las credenciales son incorrectas."]]);
    }

    public function test_user_can_login_with_valid_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
            'isValidated' => true
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertJsonFragment(["msg" => "Usuario identificado correctamente"]);

    }

}
