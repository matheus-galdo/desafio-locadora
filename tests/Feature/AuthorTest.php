<?php

namespace Tests\Feature;

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Author;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_create_author()
    {
        $data = [
            'name' => 'John Doe',
            'birthday' => '1980-05-15',
        ];

        $response = $this->postJson('/api/authors', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'birthday',
                'created_at',
                'updated_at'
            ]);
    }

    /** @test */
    public function test_can_get_all_authors()
    {
        Author::factory()->count(3)->create();

        $response = $this->getJson('/api/authors');

        $ammoutOfExpectedAuthors = 3;
        $response->assertStatus(200)
            ->assertJsonCount($ammoutOfExpectedAuthors);
    }

    /** @test */
    public function test_can_get_author_by_id()
    {
        $author = Author::factory()->create();

        $response = $this->getJson('/api/authors/' . $author->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $author->id,
                'name' => $author->name,
                'birthday' => $author->birthday,
            ]);
    }

    /** @test */
    public function test_can_update_author()
    {
        $author = Author::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'birthday' => '1990-10-20',
        ];

        $response = $this->putJson('/api/authors/' . $author->id, $data);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $author->id,
                'name' => $data['name'],
                'birthday' => $data['birthday'],
            ]);
    }

    /** @test */
    public function test_can_delete_author()
    {
        $author = Author::factory()->create();

        $response = $this->deleteJson('/api/authors/' . $author->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
}

