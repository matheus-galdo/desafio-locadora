<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_book()
    {
        $author = Author::factory()->create();

        $response = $this->postJson('/api/books', [
            'title' => 'Livro Teste',
            'publication_year' => 2022,
            'author_ids' => [$author->id],
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('books', ['title' => 'Livro Teste']);
    }

    public function test_can_get_all_books()
    {
        Book::factory()->count(3)->create();

        $response = $this->getJson('/api/books');

        $response->assertOk();
        $response->assertJsonCount(3);
    }

    public function test_can_get_a_single_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertOk();
        $response->assertJson(['title' => $book->title]);
    }

    public function test_can_update_a_book()
    {
        $book = Book::factory()->create();
        $newTitle = 'Novo Título';

        $response = $this->putJson("/api/books/{$book->id}", [
            'title' => $newTitle,
            'publication_year' => $book->publication_year,
            'author_ids' => $book->authors->pluck('id')->toArray(),
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => $newTitle]);
    }

    public function test_can_delete_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_cannot_delete_a_inexisting_book()
    {
        $fakeId = fake()->randomNumber(5);

        $response = $this->deleteJson("/api/books/{$fakeId}");

        $response->assertNotFound();
    }
}
