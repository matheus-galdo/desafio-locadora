<?php

namespace Tests\Feature;

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Author;
use App\Models\Book;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_author()
    {
        $data = Author::factory()->make()->toArray();


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

    public function test_cannot_create_author_with_missing_data()
    {
        $data = ["name" => "Fulano"];

        $response = $this->postJson('/api/authors', $data);

        $response->assertUnprocessable();
    }

    public function test_can_get_all_authors()
    {
        Author::factory()->count(3)->create();

        $response = $this->getJson('/api/authors');

        $ammoutOfExpectedAuthors = 3;
        $response->assertStatus(200)
            ->assertJsonCount($ammoutOfExpectedAuthors);
    }

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

    public function test_can_delete_author()
    {
        $author = Author::factory()->create();
        $response = $this->deleteJson('/api/authors/' . $author->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }

    public function test_cannot_delete_author_that_has_books()
    {
        $author = Author::factory()
            ->has(Book::factory())
            ->create();

        $response = $this->deleteJson('/api/authors/' . $author->id);

        $response->assertForbidden();
    }

    public function test_cannot_delete_a_inexisting_author()
    {
        $fakeId = fake()->randomNumber(5);

        $response = $this->deleteJson("/api/authors/{$fakeId}");

        $response->assertNotFound();
    }
}
