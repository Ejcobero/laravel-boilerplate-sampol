<?php

namespace App\Repository\Eloquent\Book;

use App\Models\Book;
use App\Repository\Contracts\Book\BookRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{
    /**
     * @var Book
     */
    protected $model;

    /**
     * BookRepository constructor.
     *
     * @param Book $model
     */
    public function __construct(Book $model)
    {
        $this->model = $model;
    }

    public function readBook(int $bookId): bool
    {
        $book = $this->findById($bookId);

        return $book->update([
            'has_read' => true
        ]);
    }
}
