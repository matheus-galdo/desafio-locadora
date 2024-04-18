<?php

namespace App\Services;

use App\DTO\BookDTO;
use App\Repositories\BookRepository;
use App\Models\Book;

class BookService
{
    protected $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function getAllBooks()
    {
        return $this->bookRepository->getAllBooks();
    }

    public function createBook(BookDTO $bookDTO): Book
    {
        $bookData = [
            'title' => $bookDTO->title,
            'publication_year' => $bookDTO->publication_year,
        ];

        $book = $this->bookRepository->createBook($bookData);

        $book->authors()->sync($bookDTO->author_ids);

        return $book;
    }

    public function getBookById(int $bookId): ?Book
    {
        return $this->bookRepository->getBookById($bookId);
    }

    public function updateBook(Book $book, BookDTO $bookDTO): Book
    {
        $bookData = [
            'title' => $bookDTO->title,
            'publication_year' => $bookDTO->publication_year,
        ];

        $updatedBook = $this->bookRepository->updateBook($book->id, $bookData);
        $updatedBook->authors()->sync($bookDTO->author_ids);

        return $updatedBook;
    }

    public function deleteBook(Book $book): void
    {
        $book->authors()->detach();
        $this->bookRepository->deleteBook($book->id);
    }
}