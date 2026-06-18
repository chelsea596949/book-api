<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Api\ResponseTrait;
use App\DTO\Book\BookQueryDTO;
use App\DTO\Book\BookCreateDTO;
use App\DTO\Book\BookEditDTO;

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

        // 改用validateData()來驗證合併後的陣列
        if(!$this->validateData($data, BookEditDTO::rules())) {
            return api_response(
                $this->response,
                api_error(
                    'Validation failed',
                    $this->validator->getErrors(),
                    400
                )
            );
        }

        // 取得圖片檔案（傳入在HTML input的name, 例如book_image）
        $file = $this->request->getFile('book_image');
        
        // 檢查檔案是否真的有上傳成功且沒有損壞或被移動
        if($file && $file->isValid() && !$file->hasMoved()) {
            // 呼叫FileService上傳
            $imageName = service('fileService')->uploadImage($file);
            
            if($imageName) {
                $data['image_url'] = $imageName;
            }
        }
        
        // 將合併了圖片名稱的$data封裝進DTO並執行Service
        try {
            $dto = BookEditDTO::fromArray($data);
            $responseDTO = service('bookService')->editBook($dto, $id);

            return api_response(
                $this->response,
                $responseDTO->toApiFormat()
            );
            
        } catch(\Exception $e) {
            // 捕捉Service層可能拋出的異常（例如找不到書籍）
            return api_response(
                $this->response, 
                api_error($e->getMessage(), [], 500)
            );
        }
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

        // 使用FileService處理圖片
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
