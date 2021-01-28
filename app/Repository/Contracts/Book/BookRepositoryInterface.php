<?php

namespace App\Repository\Contracts\Book;

use App\Repository\Contracts\EloquentRepositoryInterface;

interface BookRepositoryInterface extends EloquentRepositoryInterface
{
    public function readBook(int $bookId): bool;
}
