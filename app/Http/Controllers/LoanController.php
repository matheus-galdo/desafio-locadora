<?php

namespace App\Http\Controllers;

use App\DTO\LoanDTO;
use Illuminate\Http\Request;
use App\Services\LoanService;
use App\Models\Loan;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function index()
    {
        $loans = $this->loanService->getAllLoans();
        return response()->json($loans, 200);
    }

    public function store(Request $request)
    {
        $loanDTO = new LoanDTO(
            user_id: $request->input('user_id'),
            book_id: $request->input('book_id'),
            loan_date: $request->input('loan_date')
        );

        $loan = $this->loanService->createLoan($loanDTO);
        
        return response()->json($loan, 201);
    }

    public function show(Loan $loan)
    {
        return response()->json($loan, 200);
    }

    public function return(Request $request, Loan $loan)
    {
        $updatedLoan = $this->loanService->returnLoan($loan);

        return response()->json($updatedLoan, 200);
    }
}
