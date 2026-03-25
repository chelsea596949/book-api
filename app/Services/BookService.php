<?php
namespace App\Services;

use App\Transformers\BookTransformer;
use App\DTO\Book\BookQueryDTO;
use App\DTO\Book\BookResponseDTO;
use App\DTO\Book\BookCreateDTO;

class BookService {
    /**
     * Get books
     * @param BookQueryDTO $dto
     * @param int|null $id
     * @return BookResponseDTO
     */
    public function getBooks(BookQueryDTO $dto, $id=null) : BookResponseDTO
    {
        $model = model('BookModel');
        $transformer = new BookTransformer();

        $model
        ->withAuthorInfo()
        ->filterAuthorName($dto->authorName)
        ->filterSlug($dto->slug)
        ->sortBy($dto->sort, $dto->direction);

        // 單筆
        if($id !== null) {
            $book = $model->find($id);

            if(!$book) {
                return new BookResponseDTO(
                    true,
                    null,
                    [],
                    'Book not found',
                    404
                );
            }

            return new BookResponseDTO(
                false,
                $transformer->transform($book)
            );
        }

        // 分頁
        if(!empty($dto->page) && !empty($dto->perPage)) {
            $books = $model->paginate($dto->perPage);

            $meta = api_pagination($model->pager, $dto->perPage);

            return new BookResponseDTO(
                false,
                $transformer->collection($books),
                ['pagination' => $meta]
            );
        }

        // 全部資料
        $books = $model->findAll();

        return new BookResponseDTO(
            false,
            $transformer->collection($books)
        );
    }
    
    /**
     * Create a new book
     * @param BookCreateDTO $dto
     * @return BookResponseDTO
     */
    public function createBook(BookCreateDTO $dto) : BookResponseDTO
    {
        $authorModel = model('AuthorModel');
        $data = $dto->toArray();
        $authorName = $data['authorName'];
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
        unset($data['authorName']);

        $bookModel = model('BookModel');

        $createdBook = $bookModel->insert($data);
        if(!$createdBook) {
            return new BookResponseDTO(
                true,
                null,
                [],
                'Failed to create book',
                500
            );
        }

        $newBook = $bookModel->withAuthorInfo()->find($bookModel->insertID());

        return new BookResponseDTO(
            false,
            (new BookTransformer())->transform($newBook)
        );
    }

    /**
     * Delete a book
     * @param int $id
     * @return BookResponseDTO
     */
    public function deleteBook($id) : BookResponseDTO
    {
        $model = model('BookModel');

        if(!$model->find($id)) {
            return new BookResponseDTO(
                true,
                null,
                [],
                'Book not found',
                404
            );
        }

        $model->delete($id);

        return new BookResponseDTO(
            false,
            null,
            ['id' => $id],
            'Book deleted successfully',
            200
        );
    }
}