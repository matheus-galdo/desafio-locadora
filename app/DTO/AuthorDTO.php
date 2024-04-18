<?php

namespace App\DTO;

class AuthorDTO
{
    public function __construct(
        public string $name,
        public string $birthday,
        public ?int $id = null,
    ) {
    }
}
