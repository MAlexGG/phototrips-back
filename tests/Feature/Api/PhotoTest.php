<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\City;
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

    public function test_auth_user_get_all_owned_photos(): void
    {
        $this->withoutExceptionHandling();

        $user1 = User::factory()->create();
        Auth::login($user1);
        Photo::factory()->create([
            "user_id" => $user1->id
        ]);
        Auth::logout($user1);

        $user2 = User::factory()->create();
        Auth::login($user2);
        Photo::factory()->create([
            "user_id" => $user2->id
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

        City::factory()->create([
            "name" => "Tokio"
        ]);

        $response = $this->postJson('/api/photos', [
            "name" => "Oporto",
            "description" => "Lorem ipsum",
            "image" => "http://ejemplo.com/1.png",
            "user_id" => $user->id,
            "city" => "Tokio"
        ]);

        $photo = Photo::first();

        $response->assertStatus(201)
        ->assertJsonFragment(["msg" => "La fotografía se ha creado correctamente"]);
        $this->assertEquals($photo->name, "Oporto");  
    }

    public function test_auth_user_can_see_an_owned_photo()
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create([
            "id" => 1
        ]);
        Auth::login($user);

        Photo::factory()->create([
            "id" => 1,
            "user_id" => $user->id
        ]);

        $response = $this->getJson('/api/photos/1');

        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('id', 1)
            ->etc()
        );
    }
        
    public function test_auth_user_cannot_see_a_photo_of_somebody_else()
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create([
            "id" => 1
        ]);
        Auth::login($user);
        Photo::factory()->create([
            "id" => 1,
            "user_id" => $user->id
        ]);

        $response = $this->getJson('/api/photos/2');

        $response->assertJsonFragment(["msg" => "No tienes una fotografía con ese identificador"]);
    }

    public function test_auth_user_can_update_an_owned_photo()
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create();
        Auth::login($user);
        
        Photo::factory()->create([
            "id" => 1,
            "name" => "Empire State Bulding",
            "user_id" => $user->id
        ]);

        City::factory()->create([
            "name" => "New York"
        ]);

        $response = $this->putJson('/api/photos/1',[
            "name" => "Flatiron Building",
            "description" => "Lorem ipsum",
            "image" => "http://image.jpg",
            "city" => "New York"
        ]);

        $city = Photo::first();

        $response->assertJsonFragment(["msg" => "La fotografía se ha editado correctamente"]);
        $this->assertEquals("Flatiron Building", $city->name);
    }

    public function test_auth_user_receive_message_for_update_a_photo_without_city_in_database()
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create();
        Auth::login($user);
        
        Photo::factory()->create([
            "id" => 1,
            "name" => "Empire State Bulding",
            "user_id" => $user->id
        ]);

        $response = $this->putJson('/api/photos/1',[
            "name" => "Flatiron Building",
            "description" => "Lorem ipsum",
            "image" => "http://image.jpg",
            "city" => "New York"
        ]);

        $response->assertJsonFragment(["msg" => "Crea una ciudad para tu fotografía"]);
    }

    public function test_auth_user_cannot_update_a_photo_of_somebody_else()
    {
        $this->withoutExceptionHandling();
        
        $user1 = User::factory()->create([
            "id" => 1
        ]);
        Auth::login($user1);

        Photo::factory()->create([
            "id" => 1,
            "name" => "Arco del triunfo",
            "user_id" => $user1->id
        ]);

        Auth::logout($user1);

        $user2 = User::factory()->create([
            "id" => 2
        ]);
        Auth::login($user2);

        City::factory()->create([
            "name" => "New York"
        ]);

        $response = $this->putJson('/api/photos/1', [
            "name" => "Flatiron Building",
            "description" => "Lorem Ipsum",
            "image" => "http://flatiron.com",
            "city"  => "New York"
        ]);

        $response->assertJsonFragment(["msg" => "No tienes una fotografía con ese identificador"]);
    }

    public function test_auth_user_can_delete_a_photo()
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create([
            "id" => 1
        ]);
        Auth::login($user);
        Photo::factory()->create([
            "id" => 1,
            "user_id" => $user->id
        ]);

        $response = $this->deleteJson('/api/photos/1');

        $response->assertStatus(200);
        $this->assertCount(0, Photo::all());
    }

    public function test_auth_user_cannot_delete_a_photo_of_somebody_else()
    {
        $this->withoutExceptionHandling();
        
        $user1 = User::factory()->create();
        Auth::login($user1);
        Photo::factory()->create([
            "id" => 1,
            "user_id" => $user1->id
        ]);
        Auth::logout($user1);

        $user2 = User::factory()->create();
        Auth::login($user2);
        Photo::factory()->create([
            "id" => 2,
            "user_id" => $user2->id
        ]);

        $this->deleteJson('/api/photos/1');

        $this->assertCount(2, Photo::all());
    }

}
