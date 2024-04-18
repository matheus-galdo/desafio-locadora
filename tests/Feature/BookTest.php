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

    /** @test */
    public function can_create_a_book()
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

    /** @test */
    public function can_get_all_books()
    {
        Book::factory()->count(3)->create();

        $response = $this->getJson('/api/books');

        $response->assertOk();
        $response->assertJsonCount(3); // Verifica se retornou 3 livros
    }

    /** @test */
    public function can_get_a_single_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertOk();
        $response->assertJson(['title' => $book->title]);
    }

    /** @test */
    public function can_update_a_book()
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

    /** @test */
    public function can_delete_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
