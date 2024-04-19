<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Loan;
use App\Models\User;
use App\Models\Book;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_loan()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->postJson('/api/loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => now()->format('Y-m-d')
        ]);

            
        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'user_id',
                'book_id',
                'loan_date',
            ]);
    }

    public function test_can_get_loan()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $loan = Loan::factory()->for($user)->for($book)->create();

        
        $response = $this->getJson("/api/loans/{$loan->id}");
        
        $response->assertStatus(200)
            ->assertJson([
                'id' => $loan->id,
                'user_id' => $loan->user_id,
                'book_id' => $loan->book_id,
                'loan_date' => $loan->loan_date,
                'return_date' => $loan->return_date,
            ]);
    }

    public function test_should_return_loan_successfully()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->for($user)->for($book)->create();

        $response = $this->postJson("/api/loans/{$loan->id}/return");

        $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'user_id',
            'book_id',
            'loan_date',
            'return_date',
        ]);
    }

    public function test_should_fail_on_already_returned_loan()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->returned()->for($user)->for($book)->create();

        $response = $this->postJson("/api/loans/{$loan->id}/return");

        $response->assertConflict();
    }

    public function test_should_fail_on_inexisting_loan()
    {
        $fakeId = fake()->randomNumber(5);

        $response = $this->postJson("/api/loans/{$fakeId}/return");

        $response->assertNotFound();
    }
}
