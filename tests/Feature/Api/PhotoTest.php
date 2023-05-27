<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class PhotoTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     use RefreshDatabase;

    public function test_auth_user_get_all_photos(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Photo::factory()->create([
            "user_id" => $user->id
        ]);

        $response = $this->get('/api/photos');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }
    public function test_auth_user_can_create_a_photo(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->post('/api/photos', [
            "name" => "Oporto",
            "description" => "Lorem ipsum",
            "image" => "http://ejemplo.com/1.png",
            "user_id" => $user->id
        ]);

        $photo = Photo::first();

        $response->assertStatus(201)
        ->assertJsonFragment(["msg" => "La fotografía se ha creado correctamente"]);
        $this->assertEquals($photo->name, "Oporto");   
    }
}
