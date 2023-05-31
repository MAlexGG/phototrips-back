<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Country;
use App\Models\Continent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_auth_can_get_all_countries(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create();

        $response = $this->getJson('/api/countries');

        Country::first();

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }

    public function test_auth_user_can_create_a_country(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Continent::factory()->create([
            "name" => "Europa"
        ]);

        $response = $this->postJson('/api/countries', [
            "name" => "España",
            "continent" => "Europa"
        ]);

        $response->assertStatus(201)
        ->assertJsonFragment(["msg" => "El país se ha creado correctamente"]);
    }

    public function test_auth_user_cannot_create_a_country_without_a_continent(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->postJson('/api/countries', [
            "name" => "España",
            "continent" => "Europa"
        ]);

        $response->assertJsonFragment(["msg" => "Crea un continente para tu fotografía"]);
    }

    public function test_auth_user_cannot_create_a_country_that_exists_in_db(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            "name" => "España"
        ]);

        Continent::factory()->create([
            "name" => "Europa"
        ]);

        $response = $this->postJson('/api/countries', [
            "name" => "España",
            "continent" => "Europa"
        ]);

        $response->assertJsonFragment(["msg" => "El país ya existe"]);
    }


    public function test_auth_user_can_see_a_country()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            "id" => 1,
            "name" => "Dinamarca"
        ]);

        $response = $this->getJson('/api/countries/1');

        $response->assertStatus(200)
        ->assertJsonFragment(["name" => "Dinamarca"]);
    }

    public function test_auth_user_receive_a_message_for_country_not_found(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->getJson('/api/countries/1');

        $response->assertJsonFragment(["msg" => "El país no existe en la base de datos"]);
    }


    public function test_auth_user_can_update_a_country(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            "id" => 1,
            "name" => "Dinamarca"
        ]);

        Continent::create([
            "name" => "Europa"
        ]);

        $response = $this->putJson('/api/countries/1', [
            "name" => "Portugal",
            "continent" => "Europa"
        ]);

        $response->assertStatus(200)
        ->assertJsonFragment(["msg" => "El país se ha actualizado correctamente"]);
        $this->assertEquals("Portugal", Country::first()->name);
    }

    public function test_auth_user_cannot_update_a_country_that_exists_in_db(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            "id" => 1,
            "name" => "Dinamarca"
        ]);

        Country::factory()->create([
            "id" => 2,
            "name" => "Portugal"
        ]);

        Continent::create([
            "name" => "Europa"
        ]);

        $response = $this->putJson('/api/countries/1', [
            "name" => "Portugal",
            "continent" => "Europa"
        ]);

        $response->assertJsonFragment(["msg" => "El país ya existe en la base de datos"]);
    }

    public function test_auth_user_can_delete_a_country(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            "id" => 1
        ]);

        $response = $this->deleteJson('/api/countries/1');

        $response->assertStatus(200)
        ->assertJsonFragment(["msg" => "El país se ha eliminado correctamente"]);
        $this->assertCount(0, Country::all());
    }

    public function test_auth_user_receive_a_message_for_delete_a_country_that_is_not_in_database(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->deleteJson('/api/countries/1');

        $response->assertStatus(200)
        ->assertJsonFragment(["msg" => "El país no existe en la base de datos"]);
        $this->assertCount(0, Country::all());
    }
}
