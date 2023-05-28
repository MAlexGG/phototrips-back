<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\City;
use App\Models\User;
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
}
