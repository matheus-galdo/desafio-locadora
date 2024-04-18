<?php

namespace App\DTO;

class BookDTO
{
    public function __construct(
        public string $title,
        public string $publication_year,
        public array $author_ids,
        public ?int $id = null,
    ) {
    }
}
