<?php

namespace Tests\Feature;

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function it_can_create_a_note()
    {
        $response = $this->post('/notes', [
            'title' => 'Test Note',
            'content' => 'This is a test note content.',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('notes', ['title' => 'Test Note']);
    }


    public function it_can_show_a_note()
    {
        $note = Note::factory()->create();

        $response = $this->get("/notes/{$note->id}");

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson(['title' => $note->title]);
    }

    public function it_can_update_a_note()
    {
        $note = Note::factory()->create();

        $response = $this->put("/notes/{$note->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('notes', ['id' => $note->id, 'title' => 'Updated Title']);
    }

    public function it_can_delete_a_note()
    {
        $note = Note::factory()->create();

        $response = $this->delete("/notes/{$note->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

}
