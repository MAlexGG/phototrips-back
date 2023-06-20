<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_user_admin_can_see_all_users(): void
    {
        $admin = User::factory()->create([
            'id' => 1,
            'isValidated' => true,
            'isAdmin' => true
        ]);

        User::factory()->create();

        Auth::login($admin);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }

    public function test_user_admin_receive_a_message_when_there_is_no_admin_users(): void
    {
        $admin = User::factory()->create([
            'id' => 1,
            'isValidated' => true,
            'isAdmin' => true
        ]);

        Auth::login($admin);

        $response = $this->getJson('/api/users');

        $response->assertJsonFragment(['msg' => 'No existen usuarios en la base de datos']);

    }

    public function test_user_no_admin_cannot_see_all_users(): void
    {
        $user = User::factory()->create([
            'id' => 1,
            'isValidated' => true,
            'isAdmin' => false
        ]);

        Auth::login($user);

        $response = $this->getJson('/api/users');

        $response->assertJsonFragment(['msg' => 'No tienes autorización']);
    }

    public function test_user_admin_can_delete_no_admin_users(): void
    {
        $admin = User::factory()->create([
            'id' => 1,
            'isValidated' => true,
            'isAdmin' => true
        ]);

        User::factory()->create([
            'id' => 2
        ]);

        Auth::login($admin);

        $response = $this->deleteJson('/api/users/2');

        $response->assertJsonFragment(['msg' => 'Has eliminado exitosamente al usuario'])
        ->assertJsonCount(1);
    }

    public function test_user_cannot_delete_users(): void
    {
        $user = User::factory()->create([
            'id' => 1,
            'isValidated' => true,
            'isAdmin' => false
        ]);

        User::factory()->create([
            'id' => 2
        ]);

        Auth::login($user);

        $response = $this->deleteJson('/api/users/2');

        $response->assertJsonFragment(['msg' => 'No tienes autorización para eliminar usuarios']);
    }

    public function test_user_admin_receive_a_message_is_there_no_user_id_to_delete(): void
    {
        $admin = User::factory()->create([
            'id' => 1,
            'isValidated' => true,
            'isAdmin' => true
        ]);

        Auth::login($admin);

        $response = $this->deleteJson('/api/users/2');

        $response->assertJsonFragment(['msg' => 'No existe un usuario con ese identificador'])
        ->assertJsonCount(1);
    }

    public function test_user_admin_can_validate_user_registration(): void
    {
        $admin = User::factory()->create([
            'id' => 1,
            'isValidated' => true,
            'isAdmin' => true
        ]);

        $this->postJson('/api/register',[
            'id' => 2,
            'name' => 'Eli',
            'email' => 'e@mail.com',
            'password' => '123456789' 
        ]);

        Auth::login($admin);

        $response = $this->getJson('/api/validate/2');

        $user = User::find(2);
        
        $response->assertJsonFragment(['msg' => 'Usuario ha sido validado correctamente']);
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
            'id' => 2,
            'name' => 'Eli',
            'email' => 'e@mail.com',
            'password' => '123456789' 
        ]);

        Auth::login($notAdmin);

        $response = $this->getJson('/api/validate/2');
        
        $response->assertJsonFragment(['msg' => 'No tienes autorización para validar usuarios']);
    } 
}
