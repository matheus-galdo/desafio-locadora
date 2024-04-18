<?php

namespace App\Http\Controllers;

use App\DTO\BookDTO;
use Illuminate\Http\Request;
use App\Services\BookService;
use App\Models\Book;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index()
    {
        $books = $this->bookService->getAllBooks();
        return response()->json($books, 200);
    }

    public function store(Request $request)
    {
        $bookDTO = new BookDTO(
            title: $request->input('title'),
            publication_year: $request->input('publication_year'),
            author_ids: $request->input('author_ids')
        );

        $book = $this->bookService->createBook($bookDTO);
        
        return response()->json($bookDTO, 201);
    }

    public function show($id)
    {
        $book = $this->bookService->getBookById($id);

        if (!$book) {
            return response()->json(['error' => 'Livro não encontrado'], 404);
        }

        return response()->json($book, 200);
    }

    public function update(Request $request, Book $book)
    {
        $bookDTO = new BookDTO(
            title: $request->input('title') ?? $book->title,
            publication_year: $request->input('publication_year') ?? $book->publication_year,
            author_ids: $request->input('author_ids') ?? $book->authors->pluck('id')->toArray()
        );

        $updatedBook = $this->bookService->updateBook($book, $bookDTO);

        return response()->json($updatedBook, 200);
    }

    public function destroy(Book $book)
    {
        $this->bookService->deleteBook($book);

        return response()->json(['message' => 'Livro removido com sucesso'], 200);
    }
}
