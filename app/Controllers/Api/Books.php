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
        // $model       = model('BookModel');
        // $transformer = new BookTransformer();

        $data = $this->request->getGet();
        // $perPage = $this->request->getGet('perPage');
        // $page = $this->request->getGet('page');
        // $authorName = $this->request->getGet('authorName');
        // $slug = $this->request->getGet('slug');
        // $sort = $this->request->getGet('sort') ?? 'id';
        // $direction = $this->request->getGet('direction') ?? 'asc';

        $books = service('bookService')->getBooks($data, $id);

        // Otherwise, fetch all records
        // $model
        // ->withAuthorInfo()
        // ->filterAuthorName($authorName)
        // ->filterSlug($slug)
        // ->sortBy($sort, $direction);
        
        // // 單筆
        // if($id !== null) {
        //     $book = $model->find($id);

        //     if(!$book) {
        //         return api_response($this->response, api_error('Book not found', [], 404));
        //     }

        //     return api_response(
        //         $this->response,
        //         api_success('', $transformer->transform($book))
        //     );
        // }

        // // 分頁
        // if($page && $perPage) {
        //     $books = $model->paginate($perPage);

        //     $meta = api_pagination($model->pager, $perPage);

        //     return api_response(
        //         $this->response,
        //         api_success(
        //             '', 
        //             $transformer->collection($books), 
        //             [
        //                 'pagination' => $meta
        //             ]
        //         )
        //     );
        // }

        // // 全部資料
        // $books = $model->findAll();

        // return api_response(
        //     $this->response,
        //     api_success('', $transformer->collection($books))
        // );

        if($books['error']) {
            return api_response(
                $this->response,
                api_error($books['message'], [], $books['code'])
            );
        }else {
            return api_response(
                $this->response,
                api_success('', $books['data'], $books['meta'] ?? [])
            );
        }
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
            // return $this->failNotFound('Book not found');
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

        $rules = [
            'title' => 'required|string|max_length[255]|alpha_numeric_punct',
            'author_name' => 'required|string|max_length[255]|regex_match[/^[\p{Han}a-zA-Z0-9\s\.\-\_]+$/u]',
            'year' => 'required|integer|greater_than_equal_to[2000]|less_than_equal_to['.date('Y').']',
            'price' => 'required|numeric|greater_than_equal_to[0]',
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
        
        // $authorModel = model('AuthorModel');
        // $authorName = $data['author_name'];
        // $author = $authorModel->where('name', $authorName)->first();

        // // 找 author
        // $author = $authorModel
        //         ->where('name', $authorName)
        //         ->first();

        // // 不存在就新增
        // $authorId = 1;
        // if(!$author) {
        //     $authorId = $authorModel->insert([
        //         'name' => $authorName
        //     ]);
        // }else {
        //     $authorId = $author['id'];
        // }

        // $data['slug'] = url_title($data['title'], '-', true);
        // $data['author_id'] = $authorId;
        // unset($data['author_name']);

        // $bookModel = model('BookModel');

        // $bookModel->insert($data);

        // $newBook = $bookModel->withAuthorInfo()->find($bookModel->insertID());

        $newBook = service('bookService')->createBook($data);

        // return api_response(
        //     $this->response,
        //     api_success('', (new BookTransformer())->transform($newBook))
        // );
        if($newBook['error']) {
            return api_response(
                $this->response,
                api_error($newBook['message'], [], $newBook['code'])
            );
        }else {
            return api_response(
                $this->response,
                api_success('', $newBook['data'], $newBook['meta'] ?? [])
            );
        }
    }

    /**
     * Delete a book
     * DELETE /api/books/{id}
     */
    public function deleteIndex(int $id): ResponseInterface
    {
        // $model = model('BookModel');

        // if(!$model->find($id)) {
        //     return api_response($this->response, api_error('Book not found', [], 404));
        // }

        // $model->delete($id);

        $deletedBook = service('bookService')->deleteBook($id);

        // return $this->respondDeleted(['id' => $id]);
        if($deletedBook['error']) {
            return api_response(
                $this->response,
                api_error($deletedBook['message'], [], $deletedBook['code'])
            );
        }else {
            return api_response(
                $this->response,
                api_success('', $deletedBook['data'], $deletedBook['meta'] ?? [])
            );
        }
    }
}
