<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?int $id = null,
    ) {
    }
}
