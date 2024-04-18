<?php

namespace App\Services;

use App\Repositories\AuthorRepository;
use App\DTO\AuthorDTO;

class AuthorService
{
    protected $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function getAllAuthors()
    {
        return $this->authorRepository->getAllAuthors();
    }

    public function getAuthorById(int $id)
    {
        return $this->authorRepository->getAuthorById($id);
    }

    public function createAuthor(AuthorDTO $authorDTO)
    {
        $data = [
            'name' => $authorDTO->name,
            'birthday' => $authorDTO->birthday,
        ];

        return $this->authorRepository->createAuthor($data);
    }

    public function updateAuthor(int $id, AuthorDTO $authorDTO)
    {
        $data = [
            'name' => $authorDTO->name,
            'birthday' => $authorDTO->birthday,
        ];

        return $this->authorRepository->updateAuthor($id, $data);
    }

    public function deleteAuthor(int $id)
    {
        return $this->authorRepository->deleteAuthor($id);
    }
}
