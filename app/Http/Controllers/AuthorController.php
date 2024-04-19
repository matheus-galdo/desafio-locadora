<?php

namespace App\Http\Controllers;

use App\DTO\AuthorDTO;
use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    protected $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    public function index()
    {
        return $this->authorService->getAllAuthors();
    }

    public function store(AuthorRequest $request)
    {
        $authorDTO = new AuthorDTO(
            name: $request->input('name'),
            birthday: $request->input('birthday')
        );

        return $this->authorService->createAuthor($authorDTO);
    }

    public function show(string $id)
    {
        return $this->authorService->getAuthorById($id);
    }

    public function update(AuthorRequest $request, Author $author)
    {
        $authorDTO = new AuthorDTO(
            name: $request->input('name') ?? $author->name,
            birthday: $request->input('birthday') ?? $author->birthday
        );

        return $this->authorService->updateAuthor($author->id, $authorDTO);
    }

    public function destroy(Author $author)
    {
        $this->authorService->deleteAuthor($author);
        return response()->json(data: ['message' => 'Autor removido com sucesso'], status: 200);
    }
}
