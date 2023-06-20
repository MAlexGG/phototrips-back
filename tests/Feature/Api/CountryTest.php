<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Country;
use App\Models\Continent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_auth_user_can_get_all_owned_countries(): void
    {
        $this->withoutExceptionHandling();

        $user1 = User::factory()->create();
        Auth::login($user1);
        Country::factory()->create([
            'user_id' => $user1->id
        ]);

        Auth::logout($user1);

        $user2 = User::factory()->create();
        Auth::login($user2);
        Country::factory()->create([
            'user_id' => $user2->id
        ]);
        
        $response = $this->getJson('/api/countries');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }

    public function test_auth_user_receive_message_when_there_is_no_cities(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->getJson('/api/countries');

        $response->assertJsonFragment(['msg' => 'No tienes ningún país creado']);
    }

    public function test_auth_user_can_create_a_country(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Continent::factory()->create([
            'name' => 'Europa',
        ]);

        $response = $this->postJson('/api/countries', [
            'name' => 'España',
            'continent' => 'Europa',
            //'user_id' => $user->id 
        ]);

        $response->assertStatus(201)
        ->assertJsonFragment(['msg' => 'El país se ha creado correctamente']);
    }

    public function test_auth_user_cannot_create_a_country_without_a_continent(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->postJson('/api/countries', [
            'name' => 'España',
            'continent' => 'Europa'
        ]);

        $response->assertJsonFragment(['msg' => 'Crea un continente para tu fotografía']);
    }

    public function test_auth_user_cannot_create_a_country_that_exists_in_db(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            'name' => 'España',
            'user_id' => $user->id
        ]);

        Continent::factory()->create([
            'name' => 'Europa'
        ]);

        $response = $this->postJson('/api/countries', [
            'name' => 'España',
            'continent' => 'Europa'
        ]);

        $response->assertJsonFragment(['msg' => 'El país ya existe']);
    }


    public function test_auth_user_can_see_a_country(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            'id' => 1,
            'name' => 'Dinamarca',
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/countries/1');

        $response->assertStatus(200)
        ->assertJsonFragment(['name' => 'Dinamarca']);
    }

    public function test_auth_user_cannot_see_a_country_of_someone_else(): void
    {
        $this->withoutExceptionHandling();

        $user1 = User::factory()->create();
        Auth::login($user1);
        Country::factory()->create([
            'id' => 1,
            'user_id' => $user1->id
        ]);

        Auth::logout($user1);

        $user2 = User::factory()->create();
        Auth::login($user2);
        Country::factory()->create([
            'id' => 2,
            'user_id' => $user2->id
        ]);

        $response = $this->getJson('/api/countries/1');

        $response->assertJsonFragment(['msg' => 'El país no existe en la base de datos']);
    }


    public function test_auth_user_receive_a_message_for_country_not_found(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->getJson('/api/countries/1');

        $response->assertJsonFragment(['msg' => 'El país no existe en la base de datos']);
    }


    public function test_auth_user_can_update_a_country(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            'id' => 1,
            'name' => 'Dinamarca',
            'user_id' => $user->id 
        ]);

        Continent::create([
            'name' => 'Europa'
        ]);

        $response = $this->putJson('/api/countries/1', [
            'name' => 'Portugal',
            'continent' => 'Europa'
        ]);

        $response->assertStatus(200)
        ->assertJsonFragment(['msg' => 'El país se ha actualizado correctamente']);
        $this->assertEquals('Portugal', Country::first()->name);
    }

    public function test_auth_user_cannot_update_a_country_of_someone_else(): void
    {
        $this->withoutExceptionHandling();

        $user1 = User::factory()->create();
        Auth::login($user1);
        Country::factory()->create([
            'id' => 1,
            'user_id' => $user1->id
        ]);

        Auth::logout($user1);

        $user2 = User::factory()->create();
        Auth::login($user2);
        Country::factory()->create([
            'id' => 2,
            'user_id' => $user2->id
        ]);

        Continent::factory()->create([
            'name' => 'Europa'
        ]);

        $response = $this->putJson('/api/countries/1', [
            'name' => 'Portugal',
            'continent' => 'Europa'
        ]);

        $response->assertJsonFragment(['msg' => 'No tienes un país con ese identificador']);
    }

    public function test_auth_user_can_delete_a_country(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            'id' => 1,
            'user_id' => $user->id
        ]);

        $response = $this->deleteJson('/api/countries/1');

        $response->assertStatus(200)
        ->assertJsonFragment(['msg' => 'El país se ha eliminado correctamente']);
        $this->assertCount(0, Country::all());
    }

    public function test_auth_user_cannot_delete_a_country_of_someone_else(): void
    {
        $this->withoutExceptionHandling();

        $user1 = User::factory()->create();
        Auth::login($user1);
        Country::factory()->create([
            'id' => 1,
            'user_id' => $user1->id
        ]);

        Auth::logout($user1);

        $user2 = User::factory()->create();
        Auth::login($user2);
        Country::factory()->create([
            'id' => 2,
            'user_id' => $user2->id
        ]);

        $response = $this->deleteJson('/api/countries/1');

        $response->assertJsonFragment(['msg' => 'El país no existe en la base de datos']);
    }

    public function test_auth_user_receive_a_message_for_delete_a_country_that_is_not_in_database(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->deleteJson('/api/countries/1');

        $response->assertStatus(200)
        ->assertJsonFragment(['msg' => 'El país no existe en la base de datos']);
        $this->assertCount(0, Country::all());
    }

    public function test_auth_user_can_see_a_country_by_continent(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Continent::factory()->create([
            'id' => 1
        ]);

        Continent::factory()->create([
            'id' => 2
        ]);

        Country::factory()->create([
            'id' => 1,
            'continent_id' => 1,
            'user_id' => $user->id
        ]);

        Country::factory()->create([
            'id' => 2,
            'continent_id' => 2,
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/countries/continent/1');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }

    public function test_auth_user_receive_message_if_dont_have_countries_by_continent(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->getJson('/api/countries/continent/1');

        $response->assertJsonFragment(['msg' => 'No tienes países en ese continente']);

    }
}
