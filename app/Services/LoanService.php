<?php

namespace App\Services;

use App\DTO\LoanDTO;
use App\Repositories\LoanRepository;
use App\Models\Loan;

class LoanService
{
    protected $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function getAllLoans()
    {
        return $this->loanRepository->getAllLoans();
    }

    public function createLoan(LoanDTO $loanDTO): Loan
    {
        // if ($this->loanRepository->isBookAlreadyLoaned($loanDTO->book_id)) {
        //     throw new \Exception('Este livro já está emprestado.');
        // }

        $loanData = [
            'user_id' => $loanDTO->user_id,
            'book_id' => $loanDTO->book_id,
            'loan_date' => $loanDTO->loan_date,
            'due_date' => $loanDTO->return_date,
        ];
        //TODO: mandar o email da notificação

        $loan = $this->loanRepository->createLoan($loanData);

        return $loan;
    }

    public function getLoanById(int $loanId): ?Loan
    {
        return $this->loanRepository->getLoanById($loanId);
    }

    public function returnLoan(Loan $loan): Loan
    {
        if (isset($loan->return_date)) {
            abort(409, "Esse empréstimo já foi devolvido");
        }

        //vai pro repository
        $loan->update([
            "return_date" => now()
        ]);

        return $loan;
    }
}
