<?php

namespace Tests\Feature\Api;

use App\Models\Code;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_user_cannot_register_without_valid_auth_code(): void
    {
        $response = $this->postJson('/api/register',[
            "name" => "Eli",
            "email" => "e@mail.com",
            "password" => "123456789",
            "code" => '123456789'         
        ]);
        
        $response->assertJsonFragment(["msg" => 'Necesitas un código válido para registrarte, pídeselo a tu administrador']);
    }

    public function test_user_can_register_with_auth_code(): void
    {

        $code = Code::factory()->create([
            "code" => "2j2h83oi9wduq93e30djo902heidnsmolw0192e"
        ]);

        $response = $this->postJson('/api/register',[
            "name" => "Eli",
            "email" => "e@mail.com",
            "password" => "123456789",
            "code" => $code->code  
        ]);

        $response->assertJsonFragment(["msg" => "Usuario se ha registrado correctamente"]);
    }
}
