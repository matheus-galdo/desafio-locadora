<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_loan()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/loans', [
                'book_id' => $book->id,
                'loan_date' => now()->format('Y-m-d'),
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'user_id',
                'book_id',
                'loan_date',
            ]);
    }

    public function test_authenticated_user_cannot_create_loan_with_inexisting_book()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $fakeId = rand(1000, 9999);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/loans', [
                'book_id' => $fakeId,
                'loan_date' => now()->format('Y-m-d'),
            ]);

        $response->assertUnprocessable();
    }

    public function test_unauthenticated_user_cannot_create_loan()
    {
        $book = Book::factory()->create();
        $token = 'invalid token';

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/loans', [
                'book_id' => $book->id,
                'loan_date' => now()->format('Y-m-d'),
            ]);

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_get_loan()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->for($user)->for($book)->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson("/api/loans/{$loan->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $loan->id,
                'user_id' => $loan->user_id,
                'book_id' => $loan->book_id,
                'loan_date' => $loan->loan_date,
                'return_date' => $loan->return_date,
            ]);
    }

    public function test_authenticated_user_can_return_loan_successfully()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->for($user)->for($book)->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson("/api/loans/{$loan->id}/return");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'book_id',
                'loan_date',
                'return_date',
            ]);
    }


    public function test_authenticated_user_cannot_return_already_returned_loan()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->returned()->for($user)->for($book)->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson("/api/loans/{$loan->id}/return");

        $response->assertConflict();
    }





    public function test_should_fail_on_inexisting_loan()
    {
        $fakeId = fake()->randomNumber(5);

        $response = $this->postJson("/api/loans/{$fakeId}/return");

        $response->assertNotFound();
    }
}