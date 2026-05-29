<?php
namespace App\Services;

use App\Transformers\BookTransformer;
use App\DTO\Book\BookQueryDTO;
use App\DTO\Book\BookResponseDTO;
use App\DTO\Book\BookCreateDTO;
use App\DTO\Book\BookEditDTO;

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

        // 建立 cache key
        $key = 'books_'.md5(json_encode([
            'id' => $id,
            'authorName' => $dto->authorName,
            'slug' => $dto->slug,
            'title' => $dto->title,
            'search' => $dto->search,
            'sort' => $dto->sort,
            'direction' => $dto->direction,
            'page' => $dto->page,
            'perPage' => $dto->perPage
        ]));

        if($cached = safe_get_cache($key)) {
            return $cached;
        }

        // 原本 query
        $model
            ->withAuthorInfo()
            ->filterAuthorName($dto->authorName)
            ->filterTitle($dto->title)
            ->filterSearch($dto->search)
            ->filterSlug($dto->slug)
            ->sortBy($dto->sort, $dto->direction);

        // ===== 單筆 =====
        if($id !== null) {
            $book = $model->where($model->table.'.id', $id)->first();

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

            safe_save_cache($key, $response, 300); // cache 5分鐘
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

            safe_save_cache($key, $response, 300);
            return $response;
        }

        // ===== 全部 =====
        $books = $model->findAll();

        $response = new BookResponseDTO(
            false,
            $transformer->collection($books)
        );

        safe_save_cache($key, $response, 300);
        return $response;
    }
    
    /**
     * Create a new book
     * @param BookCreateDTO $dto
     * @return BookResponseDTO
     */
    public function createBook(BookCreateDTO $dto) : BookResponseDTO
    {
        $data = $dto->toArray();
        
        // 使用通用方法處理作者
        $data['author_id'] = $this->getOrCreateAuthorId($data['authorName']);
        unset($data['authorName']);

        $data['slug'] = url_title($data['title'], '-', true);
        
        $bookModel = model('BookModel');
        $insertId = $bookModel->insert($data);

        if(!$insertId) {
            return new BookResponseDTO(true, null, [], 'Failed to create book', 500);
        }

        $newBook = $bookModel->withAuthorInfo()->find($insertId);
        safe_clean_cache();

        return new BookResponseDTO(false, (new BookTransformer())->transform($newBook));
    }
    
    /**
     * Edit an existing book
     * @param BookEditDTO $dto
     * @param int $id
     * @return BookResponseDTO
     */
    public function editBook(BookEditDTO $dto, int $id) : BookResponseDTO
    {
        $data = $dto->toArray(true); 
        $bookId = $id;

        if(!$bookId) {
            return new BookResponseDTO(true, null, [], 'Wrong book ID', 500);
        }

        // 只有在資料中有傳入 author_name 時才處理
        if(isset($data['author_name'])) {
            $data['author_id'] = $this->getOrCreateAuthorId($data['author_name']);
            unset($data['author_name']);
        }

        if(isset($data['title'])) {
            $data['slug'] = url_title($data['title'], '-', true);
        }

        if(empty($data)) {
            return new BookResponseDTO(true, null, [], 'No data to update', 400);
        }

        $bookModel = model('BookModel');
        if(!$bookModel->update($bookId, $data)) {
            return new BookResponseDTO(true, null, [], 'Failed to edit book', 500);
        }

        $newBook = $bookModel->withAuthorInfo()->find($bookId);
        safe_clean_cache();

        return new BookResponseDTO(false, (new BookTransformer())->transform($newBook));
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

        safe_clean_cache(); // 清除 cache

        return new BookResponseDTO(
            false,
            null,
            ['id' => $id],
            'Book deleted successfully',
            200
        );
    }

    /**
     * 根據姓名取得作者 ID，若不存在則新增
     * @param string $authorName
     * @return int|string|null
     */
    private function getOrCreateAuthorId(string $authorName)
    {
        $authorModel = model('AuthorModel');
        
        // 尋找現有作者
        $author = $authorModel->where('name', $authorName)->first();

        if(!$author) {
            // 不存在就新增並回傳新 ID
            return $authorModel->insert(['name' => $authorName]);
        }

        // 存在就回傳現有 ID
        return $author['id'];
    }
}