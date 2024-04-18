<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository
{
    public function getAllBooks()
    {
        return Book::all();
    }

    public function getBookById(int $id)
    {
        return Book::findOrFail($id);
    }

    public function createBook(array $data)
    {
        return Book::create($data);
    }

    public function updateBook(int $id, array $data)
    {
        $book = $this->getBookById($id);
        $book->update($data);
        return $book;
    }

    public function deleteBook(int $id)
    {
        $book = $this->getBookById($id);
        $book->delete();
    }
}
