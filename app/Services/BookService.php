<?php
namespace App\Services;

use App\Transformers\BookTransformer;

class BookService {
    /**
     * Get books
     * @param array $data
     * @param int|null $id
     * @return array
     */
    public function getBooks($data, $id=null) {
        $model = model('BookModel');
        $transformer = new BookTransformer();

        $model
        ->withAuthorInfo()
        ->filterAuthorName($data['authorName'] ?? null)
        ->filterSlug($data['slug'] ?? null)
        ->sortBy($data['sort'] ?? 'id', $data['direction'] ?? 'asc');

        // 單筆
        if($id !== null) {
            $book = $model->find($id);

            if(!$book) {
                return [
                    'error' => true,
                    'message' => 'Book not found',
                    'code' => 404,
                    'data' => null,
                    'meta' => []
                ];
            }

            return [
                'error' => false,
                'data' => $transformer->transform($book),
                'meta' => []
            ];
        }

        // 分頁
        if(!empty($data['page']) && !empty($data['perPage'])) {
            $books = $model->paginate($data['perPage']);

            $meta = api_pagination($model->pager, $data['perPage']);

            return [
                'error' => false,
                'data' => $transformer->collection($books),
                'meta' => ['pagination' => $meta]
            ];
        }

        // 全部資料
        $books = $model->findAll();

        return [
            'error' => false,
            'data' => $transformer->collection($books),
            'meta' => []
        ];
    }
    
    /**
     * Create a new book
     * @param array $data
     * @return array
     */
    public function createBook($data) {
        $authorModel = model('AuthorModel');
        $authorName = $data['author_name'];
        $author = $authorModel->where('name', $authorName)->first();

        // 找 author
        $author = $authorModel
                ->where('name', $authorName)
                ->first();

        // 不存在就新增
        $authorId = 1;
        if(!$author) {
            $authorId = $authorModel->insert([
                'name' => $authorName
            ]);
        }else {
            $authorId = $author['id'];
        }

        $data['slug'] = url_title($data['title'], '-', true);
        $data['author_id'] = $authorId;
        unset($data['author_name']);

        $bookModel = model('BookModel');

        $createdBook = $bookModel->insert($data);
        if(!$createdBook) {
            return [
                'error' => true,
                'message' => 'Failed to create book',
                'code' => 500,
                'data' => null,
                'meta' => []
            ];
        }

        $newBook = $bookModel->withAuthorInfo()->find($bookModel->insertID());

        // return $newBook;
        return [
            'error' => false,
            'data' => (new BookTransformer())->transform($newBook),
            'meta' => []
        ];
    }

    /**
     * Delete a book
     * @param int $id
     * @return array
     */
    public function deleteBook($id) {
        $model = model('BookModel');

        if(!$model->find($id)) {
            return [
                'error' => true,
                'message' => 'Book not found',
                'code' => 404,
                'data' => null,
                'meta' => []
            ];
        }

        $model->delete($id);

        return [
            'error' => false,
            'data' => ['id' => $id],
            'meta' => []
        ];
    }
}