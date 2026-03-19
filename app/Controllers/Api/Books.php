<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Transformers\BookTransformer;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Api\ResponseTrait;

class Books extends BaseController
{
    use ResponseTrait;

    /** 
     * List one or many resources
     * GET /api/books
     *    and
     * GET /api/books/{id}
     */
    public function getIndex(?int $id=null): ResponseInterface
    {
        $model       = model('BookModel');
        $transformer = new BookTransformer();

        // If an ID is provided, fetch a single record
        if ($id !== null) {
            $book = $model->withAuthorInfo()->find($id);

            if (! $book) {
                return $this->failNotFound('Book not found');
            }

            return $this->respond($transformer->transform($book));
        }

        $perPage = $this->request->getGet('perPage') ?? 10;
        $page = $this->request->getGet('page') ?? 1;

        if ($page < 0) {
            return $this->failValidationErrors(['page' => 'Page number must be greater than 0']);
        }
        if ($perPage < 0) {
            return $this->failValidationErrors(['perPage' => 'Per page number must be greater than 0']);
        }

        // Otherwise, fetch all records
        $books = $model->withAuthorInfo();

        return $this->paginate($books, $perPage, transformWith: BookTransformer::class);
    }

     /**
     * Update a book
     *
     * PUT /api/books/{id}
     */
    public function putIndex(int $id): ResponseInterface
    {
        $data = $this->request->getRawInput();

        $rules = [
            'title'     => 'required|string|max_length[255]',
            'author_id' => 'required|integer|is_not_unique[authors.id]',
            'year'      => 'required|integer|greater_than_equal_to[2000]|less_than_equal_to[' . date('Y') . ']',
        ];

        if (! $this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $model = model('BookModel');

        if (! $model->find($id)) {
            return $this->failNotFound('Book not found');
        }

        $model->update($id, $data);

        $updatedBook = $model->withAuthorInfo()->find($id);

        return $this->respond((new BookTransformer())->transform($updatedBook));
    }

    /**
     * Create a new book
     *
     * POST /api/books
     */
    public function postIndex(): ResponseInterface
    {
        $data = $this->request->getPost();

        $rules = [
            'title'     => 'required|string|max_length[255]',
            'author_id' => 'required|integer|is_not_unique[authors.id]',
            'year'      => 'required|integer|greater_than_equal_to[2000]|less_than_equal_to[' . date('Y') . ']',
        ];

        if (! $this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $model = model('BookModel');
        $model->insert($data);

        $newBook = $model->withAuthorInfo()->find($model->insertID());

        return $this->respondCreated((new BookTransformer())->transform($newBook));
    }

    /**
     * Delete a book
     *
     * DELETE /api/books/{id}
     */
    public function deleteIndex(int $id): ResponseInterface
    {
        $model = model('BookModel');

        if (! $model->find($id)) {
            return $this->failNotFound('Book not found');
        }

        $model->delete($id);

        return $this->respondDeleted(['id' => $id]);
    }
}
