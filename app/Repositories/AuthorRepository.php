<?php

namespace App\Repositories;

use App\Models\Author;

class AuthorRepository
{
    public function getAllAuthors()
    {
        return Author::all();
    }

    public function getAuthorById(int $id)
    {
        return Author::findOrFail($id);
    }

    public function createAuthor(array $data)
    {
        return Author::create($data);
    }

    public function updateAuthor(int $id, array $data)
    {
        $author = $this->getAuthorById($id);
        $author->update($data);
        return $author;
    }

    public function deleteAuthor(int $id)
    {
        $author = $this->getAuthorById($id);
        $author->delete();
    }
}
