<?php

namespace App\Repositories;

use App\Models\Loan;

class LoanRepository
{
    public function getAllLoans()
    {
        return Loan::all();
    }

    public function getLoanById(int $id)
    {
        return Loan::findOrFail($id);
    }

    public function createLoan(array $data)
    {
        return Loan::create($data);
    }

    public function updateLoan(int $id, array $data)
    {
        $loan = $this->getLoanById($id);
        $loan->update($data);
        return $loan;
    }

    public function deleteLoan(int $id)
    {
        $loan = $this->getLoanById($id);
        $loan->delete();
    }
}
