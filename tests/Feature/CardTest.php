<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Card;

class CardTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function testIfCanListCards()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/api/photos');
        $response->assertStatus(200);
    }

    public function testIfCanCreateACard()
    {
        $this->withoutExceptionHandling();

        Storage::fake('public');

        $file = UploadedFile::fake()->image('bali.jpg');
        $card = [
            'title' => 'bali',
            'image' => $file,
            'description' => 'Lorem ipsum'
        ];

        Storage::disk('public')->exists($file->hashName());

        $response = $this->post('/api/photos', $card);
        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'bali'])
            ->assertJsonFragment(['description' => 'Lorem ipsum']);
    }

    public function testIfCanUpdateACard()
    {
        $this->withoutExceptionHandling();

        $card = Card::factory()->create();
        $card->title = 'bali';

        $response = $this->post('/api/photos/' . $card->id, $card->toArray());
        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'bali']);
    }

    public function testIfCanDeleteACard()
    {
        $this->withoutExceptionHandling();

        $card = Card::factory()->create();

        $response = $this->delete('/api/photos/' . $card->id);
        $response->assertStatus(200);
    }

    public function testIfCanShowACardById()
    {
        $this->withoutExceptionHandling();

        $card = Card::factory()->create();

        $response = $this->get('/api/photos/' . $card->id);
        $response->assertOk()
            ->assertSee($card->title);
    }

    public function testIfCanSearchACard()
    {
        $this->withExceptionHandling();

        $card = Card::factory()->create([
            'title' => 'bali',
            'image' => 'bali.jpg',
            'description' => 'Lorem ipsum'
        ]);

        $response = $this->get('/api/search/bali');
        $data = ['title' => $card->title];

        $response->assertJsonFragment($data);
    }
}
