<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Book;

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

    public function hasBooksLinked(Author $author): bool
    {
        return Book::whereHas('authors', function ($builder) use ($author) {
            $builder->where('author_id', $author->id);
        })->exists();
    }
}
