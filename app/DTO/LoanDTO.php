<?php

namespace App\DTO;

class LoanDTO
{
    public function __construct(
        public int $user_id,
        public int $book_id,
        public string $loan_date,
        public ?string $return_date = null,
        public ?int $id = null,
    ) {
    }
}
