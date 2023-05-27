<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $response = $this->getJson('/api/photos');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }

    public function test_auth_user_can_create_a_photo(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->postJson('/api/photos', [
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

    public function test_auth_user_can_see_a_photo()
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create();
        Auth::login($user);

        Photo::factory()->create([
            "id" => 1
        ]);

        $response = $this->getJson('/api/photos/1');

        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('id', 1)
            ->etc()
        );


        
    }
}
