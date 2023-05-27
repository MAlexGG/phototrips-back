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
        $user = User::factory()->create();

        Auth::login($user);

        Photo::factory()->create([
            "user_id" => $user->id
        ]);

        Photo::all();

        $response = $this->get('/api/photos');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }
}
