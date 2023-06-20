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

    public function test_auth_user_can_see_all_owned_cities(): void
    {
        $this->withoutExceptionHandling();

        $user1 = User::factory()->create();
        Auth::login($user1);
        City::factory()->create([
            'user_id' => $user1->id
        ]);

        Auth::logout($user1);

        $user2 = User::factory()->create();
        Auth::login($user2);
        City::factory()->create([
            'user_id' => $user2->id
        ]);

        $response = $this->getJson('/api/cities');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }

    public function test_auth_user_receive_message_when_there_is_no_cities(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->getJson('/api/cities');

        $response->assertJsonFragment(['msg' => 'No tienes ninguna ciudad creada']);
    }

    public function test_auth_user_can_create_a_city(): void 
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            'name' => 'Ecuador',
            'user_id' => $user->id
        ]);

        $response = $this->postJson('/api/cities', [
            'name' => 'quito',
            'country' => 'Ecuador'
        ]);

        $city = City::first();

        $response->assertStatus(201)
        ->assertJsonFragment(['msg' => 'La ciudad se ha creado correctamente']);
        $this->assertEquals($city->name, 'Quito');  
    }

    public function test_auth_user_cannot_create_a_city_without_country(): void 
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->postJson('/api/cities', [
            'name' => 'Quito',
            'country' => 'Ecuador'
        ]);

        $response->assertJsonFragment(['msg' => 'Crea un país para tu fotografía']);
    }

    public function test_auth_user_cannot_create_a_city_that_exists_in_db(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        City::factory()->create([
            'name' => 'Quito',
            'user_id' => $user->id
        ]);

        Country::factory()->create([
            'name' => 'Ecuador',
            'user_id' => $user->id
        ]);

        $response = $this->postJson('/api/cities', [
            'name' => 'Quito',
            'country' => 'Ecuador'
        ]);

        $response->assertJsonFragment(['msg' => 'La ciudad ya existe']);
    }

    public function test_auth_user_can_see_a_city(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        City::factory()->create([
            'id' => 1,
            'name' => 'New York',
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/cities/1');

        $response->assertStatus(200)
        ->assertJsonFragment(['name' => 'New York']);
    }

    public function test_auth_user_receive_a_message_for_city_not_found(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->getJson('/api/cities/1');

        $response->assertJsonFragment(['msg' => 'La ciudad no existe en la base de datos']);

    }

    public function test_auth_user_can_update_a_city(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            'id' => 1,
            'name' => 'Estados Unidos de Norte América',
            'user_id' => $user->id
        ]);

        City::factory()->create([
            'id' => 1,
            'name' => 'new york',
            'country_id' => 1,
            'user_id' => $user->id
        ]);

        $response = $this->putJson('/api/cities/1',[
            'name' => 'Miami',
            'country' => 'Estados Unidos de Norte América',
        ]);

        $response->assertStatus(200)
        ->assertJsonFragment(['msg' => 'La ciudad se ha actualizado correctamente']);
    }

    public function test_auth_user_receive_a_message_for_cities_not_found(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create([
            'id' => 2
        ]);

        Auth::login($user);

        Country::factory()->create([
            'name' => 'Japón',
            'user_id' => $user->id
        ]);

        $response = $this->putJson('/api/cities/1',[
            'name' => 'kyoto',
            'country' => 'Japón',
        ]);

        $response->assertJsonFragment(['msg' => 'No tienes una ciudad con ese identificador']);

        

    }

    public function test_auth_user_can_delete_a_city(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        City::factory()->create([
            'id' => 1,
            'name' => 'Lisboa',
            'user_id' => $user->id
        ]);

        $response = $this->deleteJson('/api/cities/1');

        $response->assertStatus(200)
        ->assertJsonFragment(['msg' => 'La ciudad se ha eliminado correctamente']);
        $this->assertCount(0, City::all());
    }

    public function test_auth_user_receive_a_message_for_delete_a_city_that_is_not_in_database(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->deleteJson('/api/cities/1');

        $response->assertJsonFragment(['msg' => 'La ciudad no existe en la base de datos']);
    }

    public function test_auth_user_can_see_a_city_by_country(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            'id' => 1,
            'user_id' => $user->id
        ]);

        Country::factory()->create([
            'id' => 2,
            'user_id' => $user->id
        ]);
        
        City::factory()->create([
            'country_id' => 1,
            'user_id' => $user->id
        ]);

        City::factory()->create([
            'country_id' => 2,
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/cities/country/1');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }

    public function test_auth_user_receive_message_if_dont_have_cities_by_country(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Auth::login($user);

        Country::factory()->create([
            'id' => 1,
            'user_id' => $user->id
        ]);

        City::factory()->create([
            'country_id' => 1,
            'user_id' => $user->id
        ]);

        $response = $this->getJson('/api/cities/country/2');

        $response->assertJsonFragment(['msg' => 'No tienes ciudades en ese país']);
    }
}
