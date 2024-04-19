<?php

namespace Tests\Feature;

use App\DTO\LoanDTO;
use App\Models\Author;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Notifications\LoanCreatedNotification;
use App\Repositories\LoanRepository;
use App\Services\LoanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LoanServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateLoanSendsNotification()
    {
        // Criar um usuário fictício (ou usar um usuário existente)
        $user = User::factory()->create();
        $book = Book::factory()
            ->has(Author::factory())
            ->create();

        // Simular a criação de um empréstimo (substitua com os dados necessários)
        $loanData = new LoanDTO(
            user_id: $user->id,
            book_id: $book->id,
            loan_date: now()->format('Y-m-d')
        );

        // dd($loanData);

        // Desativar o envio real de e-mails durante o teste
        Notification::fake();

        // Instanciar o serviço de empréstimo (substitua com o serviço apropriado)
        $loanRepository = new LoanRepository();
        $loanService = new LoanService($loanRepository);

        // Chamar o método para criar o empréstimo
        $loan = $loanService->createLoan($loanData);

        // Verificar se a notificação foi enviada para o usuário correto
        Notification::assertSentTo($user, LoanCreatedNotification::class);
    }
}
