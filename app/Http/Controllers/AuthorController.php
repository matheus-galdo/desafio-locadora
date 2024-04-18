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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->authorService->getAllAuthors();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuthorRequest $request)
    {
        $authorDTO = new AuthorDTO(
            name: $request->input('name'),
            birthday: $request->input('birthday')
        );

        return $this->authorService->createAuthor($authorDTO);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->authorService->getAuthorById($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AuthorRequest $request, Author $author)
    {
        $authorDTO = new AuthorDTO(
            name: $request->input('name') ?? $author->name,
            birthday: $request->input('birthday') ?? $author->birthday
        );

        return $this->authorService->updateAuthor($author->id, $authorDTO);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorService->deleteAuthor($id);
        return response()->json(data: [], status: 204);
    }
}
