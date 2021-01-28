<?php

namespace App\Http\Requests;

use App\Repository\Contracts\Book\BookRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    private $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function persist(int $bookId = null)
    {
        switch ($this->method()) {
            case 'POST':
                return $this->bookRepository->create($this->all());

            case 'PUT':
                return $this->bookRepository->update($bookId, $this->all());

            default:
                return null;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:10'
        ];
    }
}
