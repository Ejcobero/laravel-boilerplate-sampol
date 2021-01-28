<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Repository\Contracts\Book\BookRepositoryInterface;
use App\Repository\Eloquent\Book\BookRepository;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function index()
    {
        $books = $this->bookRepository->all();

        return response()->json($books);
    }

    public function show(int $bookId)
    {
        return response()->json(
            $this->bookRepository->findById($bookId)
        );
    }

    // POST
    public function store(BookRequest $request)
    {
        return response()->json($request->persist(), 201);
    }

    // PUT
    public function update(BookRequest $request, int $bookId)
    {
        return response()->json([
            'resource' => $request->persist($bookId),
            'message' => 'ARF! A book has been updated.'
        ], 200); // OK
    }

    public function destroy(int $bookId)
    {
        return response()->json(
            $this->bookRepository->deleteById($bookId)
        );
    }

    public function restore(int $bookId)
    {
        return response()->json([
            'resource' => $this->bookRepository->restoreById($bookId),
            'message' => 'Jinnytty'
        ]);
    }

    public function forceDestroy(int $bookId)
    {
        return response()->json([
            'resource' => $this->bookRepository->permanentlyDeleteById($bookId),
            'message' => 'Bye d0g'
        ]);
    }
}
