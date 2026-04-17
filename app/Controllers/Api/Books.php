<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Transformers\BookTransformer;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Api\ResponseTrait;
use App\DTO\Book\BookQueryDTO;
use App\DTO\Book\BookCreateDTO;

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
        $dto = new BookQueryDTO($this->request->getGet());

        $responseDTO = service('bookService')->getBooks($dto, $id);

        return api_response(
            $this->response,
            $responseDTO->toApiFormat()
        );
    }

     /**
     * Update a book
     * PUT /api/books/{id}
     */
    public function putIndex(int $id): ResponseInterface
    {
        $data = $this->request->getRawInput();

        $rules = [
            'title' => 'required|string|max_length[255]',
            'author_id' => 'required|integer|is_not_unique[authors.id]',
            'year' => 'required|integer|greater_than_equal_to[2000]|less_than_equal_to[' . date('Y') . ']',
        ];

        if(!$this->validate($rules)) {
            return api_response(
                $this->response, 
                api_error(
                    'Validation failed', 
                    $this->validator->getErrors(), 
                    400
                )
            );
        }

        $model = model('BookModel');

        if(!$model->find($id)) {
            return api_response($this->response, api_error('Book not found', [], 404));
        }

        $model->update($id, $data);

        $updatedBook = $model->withAuthorInfo()->find($id);

        return $this->respond((new BookTransformer())->transform($updatedBook));
    }

    /**
     * Create a new book
     * POST /api/books
     */
    public function postIndex(): ResponseInterface
    {
        $data = $this->request->getPost();

        if(!$this->validate(BookCreateDTO::rules())) {
            return api_response(
                $this->response,
                api_error(
                    'Validation failed',
                    $this->validator->getErrors(),
                    400
                )
            );
        }

        // 2使用 FileService 處理圖片
        $imageName = service('fileService')->uploadImage($this->request->getFile('book_image'));
        if($imageName) {
            $data['image_url'] = $imageName;
        }

        $dto = BookCreateDTO::fromArray($data);

        $responseDTO = service('bookService')->createBook($dto);

        return api_response(
            $this->response,
            $responseDTO->toApiFormat()
        );
    }

    /**
     * Delete a book
     * DELETE /api/books/{id}
     */
    public function deleteIndex(int $id): ResponseInterface
    {
        $responseDTO = service('bookService')->deleteBook($id);

        return api_response(
            $this->response,
            $responseDTO->toApiFormat()
        );
    }
}
