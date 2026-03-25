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
        $cache = cache();
        $model = model('BookModel');
        $transformer = new BookTransformer();

        // 建立 cache key
        $key = 'books_'.md5(json_encode([
            'id' => $id,
            'authorName' => $dto->authorName,
            'slug' => $dto->slug,
            'sort' => $dto->sort,
            'direction' => $dto->direction,
            'page' => $dto->page,
            'perPage' => $dto->perPage
        ]));

        // 先讀 cache
        if($cached = $cache->get($key)) {
            return $cached;
        }

        // 原本 query
        $model
            ->withAuthorInfo()
            ->filterAuthorName($dto->authorName)
            ->filterSlug($dto->slug)
            ->sortBy($dto->sort, $dto->direction);

        // ===== 單筆 =====
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

            $response = new BookResponseDTO(
                false,
                $transformer->transform($book)
            );

            $cache->save($key, $response, 300); // cache 5分鐘
            return $response;
        }

        // ===== 分頁 =====
        if(!empty($dto->page) && !empty($dto->perPage)) {
            $books = $model->paginate($dto->perPage);
            $meta = api_pagination($model->pager, $dto->perPage);

            $response = new BookResponseDTO(
                false,
                $transformer->collection($books),
                ['pagination' => $meta]
            );

            $cache->save($key, $response, 300);
            return $response;
        }

        // ===== 全部 =====
        $books = $model->findAll();

        $response = new BookResponseDTO(
            false,
            $transformer->collection($books)
        );

        $cache->save($key, $response, 300);
        return $response;
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

        cache()->clean(); // 清除 cache

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

        cache()->clean(); // 清除 cache

        return new BookResponseDTO(
            false,
            null,
            ['id' => $id],
            'Book deleted successfully',
            200
        );
    }
}