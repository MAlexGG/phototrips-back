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
}
