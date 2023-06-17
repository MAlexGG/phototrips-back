<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_user_admin_can_validate_user_registration(): void
    {
        $admin = User::factory()->create([
            'id' => 1,
            'isValidated' => true,
            'isAdmin' => true
        ]);

        $this->postJson('/api/register',[
            "id" => 2,
            "name" => "Eli",
            "email" => "e@mail.com",
            "password" => "123456789" 
        ]);

        Auth::login($admin);

        $response = $this->getJson('/api/validate/2');

        $user = User::find(2);
        
        $response->assertJsonFragment(["msg" => "Usuario ha sido validado correctamente"]);
        $this->assertCount(2, User::all());
        $this->assertTrue($user->isValidated == true);
    } 

    public function test_user_not_admin_cannot_validate_user_registration(): void
    {
        $notAdmin = User::factory()->create([
            'id' => 1,
            'isValidated' => true,
            'isAdmin' => false
        ]);

        $this->postJson('/api/register',[
            "id" => 2,
            "name" => "Eli",
            "email" => "e@mail.com",
            "password" => "123456789" 
        ]);

        Auth::login($notAdmin);

        $response = $this->getJson('/api/validate/2');
        
        $response->assertJsonFragment(["msg" => "No tienes authorización para validar usuarios"]);
    } 
}
