<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_book()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $author = Author::factory()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/books', [
                'title' => 'Livro Teste',
                'publication_year' => 2022,
                'author_ids' => [$author->id],
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('books', ['title' => 'Livro Teste']);
    }

    public function test_cannot_create_a_book_with_an_inexisting_author_id()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $fakeId = rand(1000, 9999);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/books', [
                'title' => 'Livro Teste',
                'publication_year' => 2022,
                'author_ids' => [$fakeId],
            ]);


        $response->assertUnprocessable();
    }

    public function test_can_get_all_books()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Book::factory()->count(3)->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/books');

        $response->assertOk();
        $response->assertJsonCount(3);
    }

    public function test_can_get_a_single_book()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $book = Book::factory()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson("/api/books/{$book->id}");

        $response->assertOk();
        $response->assertJson(['title' => $book->title]);
    }

    public function test_cannot_get_an_inexisting_book()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $fakeId = rand(1000, 9999);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson("/api/books/{$fakeId}");

        $response->assertNotFound();
    }

    public function test_can_update_a_book()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $book = Book::factory()
            ->has(Author::factory())
            ->create();

        $newTitle = 'Novo Título';

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->putJson("/api/books/{$book->id}", [
                'title' => $newTitle,
                'publication_year' => $book->publication_year,
                'author_ids' => $book->authors->pluck('id')->toArray(),
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => $newTitle]);
    }

    public function test_can_delete_a_book()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $book = Book::factory()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson("/api/books/{$book->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_cannot_delete_an_inexisting_book()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $fakeId = rand(1000, 9999);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson("/api/books/{$fakeId}");

        $response->assertNotFound();
    }
}
