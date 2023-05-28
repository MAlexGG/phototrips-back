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

        $continent = Continent::factory()->create([
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
}
