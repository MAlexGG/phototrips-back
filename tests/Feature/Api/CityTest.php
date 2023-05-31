<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\City;
use App\Models\User;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CityTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_auth_user_can_see_all_cities(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        City::factory()->create();

        $response = $this->getJson('/api/cities');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }

    public function test_auth_user_can_create_a_city(): void 
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            "name" => "Ecuador"
        ]);

        $response = $this->postJson('/api/cities', [
            "name" => "Quito",
            "country" => "Ecuador"
        ]);

        $city = City::first();

        $response->assertStatus(201)
        ->assertJsonFragment(["msg" => "La ciudad se ha creado correctamente"]);
        $this->assertEquals($city->name, "Quito");  
    }

    public function test_auth_user_cannot_create_a_city_without_country(): void 
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->postJson('/api/cities', [
            "name" => "Quito",
            "country" => "Ecuador"
        ]);

        $response->assertJsonFragment(["msg" => "Crea un país para tu fotografía"]);
    }

    public function test_auth_user_cannot_create_a_city_that_exists_in_db(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        City::factory()->create([
            "name" => "Quito"
        ]);

        Country::factory()->create([
            "name" => "Ecuador"
        ]);

        $response = $this->postJson('/api/cities', [
            "name" => "Quito",
            "country" => "Ecuador"
        ]);

        $response->assertJsonFragment(["msg" => "La ciudad ya existe"]);
    }

    public function test_auth_user_can_see_a_city(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        City::factory()->create([
            "id" => 1,
            "name" => "New York"
        ]);

        $response = $this->getJson('/api/cities/1');

        $response->assertStatus(200)
        ->assertJsonFragment(["name" => "New York"]);
    }

    public function test_auth_user_receive_a_message_for_city_not_found(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->getJson('/api/cities/1');

        $response->assertJsonFragment(["msg" => "La ciudad no existe en la base de datos"]);

    }

    public function test_auth_user_can_update_a_city(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        City::factory()->create([
            "id" => 1,
            "name" => "New York"
        ]);

        Country::factory()->create([
            "id" => 1,
            "name" => "Estados Unidos de Norte América"
        ]);

        $response = $this->putJson('/api/cities/1',[
            "name" => "Nueva York",
            "country" => "Estados Unidos de Norte América"
        ]);

        $response->assertStatus(200)
        ->assertJsonFragment(["msg" => "La ciudad se ha actualizado correctamente"]);
    }

    public function test_auth_user_cannot_update_a_city_that_exists_in_db(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        City::factory()->create([
            "id" => 1,
            "name" => "Lisboa"
        ]);

        City::factory()->create([
            "id" => 2,
            "name" => "Oporto"
        ]);

        Country::factory()->create([
            "id" => 1,
            "name" => "Portugal"
        ]);

        $response = $this->putJson('/api/cities/2', [
            "name" => "Lisboa",
            "country" => "Portugal"
        ]);

        $response->assertJsonFragment(["msg" => "La ciudad ya existe en la base de datos"]);
    }

    public function test_auth_user_can_delete_a_city(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        City::factory()->create([
            "id" => 1,
            "name" => "Lisboa"
        ]);

        $response = $this->deleteJson('/api/cities/1');

        $response->assertStatus(200)
        ->assertJsonFragment(["msg" => "La ciudad se ha eliminado correctamente"]);
        $this->assertCount(0, City::all());
    }
}
