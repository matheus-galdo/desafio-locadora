<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_author()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = Author::factory()->make()->toArray();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/authors', $data);

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
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = Author::factory()->make()->toArray();
        unset($data['birthday']);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/authors', $data);

        $response->assertUnprocessable();
    }

    public function test_can_get_all_authors()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Author::factory()->count(3)->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/authors');

        $ammoutOfExpectedAuthors = 3;
        $response->assertStatus(200)
            ->assertJsonCount($ammoutOfExpectedAuthors);
    }

    public function test_can_get_author_by_id()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $author = Author::factory()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/authors/' . $author->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $author->id,
                'name' => $author->name,
                'birthday' => $author->birthday,
            ]);
    }

    public function test_cannot_get_an_inexisting_author_by_id()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $fakeId = fake()->randomNumber(5);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/authors/' . $fakeId);

        $response->assertNotFound();
    }

    public function test_can_update_author()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $author = Author::factory()->create();
        $data = $author->toArray();
        $data['name'] = 'Updated Name';

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->putJson('/api/authors/' . $author->id, $data);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $author->id,
                'name' => $data['name'],
                'birthday' => $data['birthday'],
            ]);
    }

    public function test_can_delete_author()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $author = Author::factory()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson('/api/authors/' . $author->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }

    public function test_cannot_delete_author_that_has_books()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $author = Author::factory()
            ->has(Book::factory())
            ->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson('/api/authors/' . $author->id);

        $response->assertForbidden();
    }

    public function test_cannot_delete_an_inexisting_author()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $fakeId = fake()->randomNumber(5);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson("/api/authors/{$fakeId}");

        $response->assertNotFound();
    }
}
