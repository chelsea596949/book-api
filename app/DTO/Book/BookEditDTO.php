<?php
namespace App\DTO\Book;

class BookEditDTO
{
    public ?string $title = null;
    public ?string $authorName = null;
    public ?int $year = null;
    public ?float $price = null;
    public ?string $image_url = null;

    public static function rules(): array
    {
        return [
            // 使用 permit_empty，確保沒傳或傳空值時不會報錯
            'title'       => 'permit_empty|string|max_length[255]|alpha_numeric_punct',
            'author_name' => 'permit_empty|string|max_length[255]|regex_match[/^[\p{Han}a-zA-Z0-9\s\.\-\_]+$/u]',
            'year'        => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to['.date('Y').']',
            'price'       => 'permit_empty|numeric|greater_than_equal_to[0]',
            'book_image'  => 'permit_empty|max_size[book_image,2048]|is_image[book_image]|mime_in[book_image,image/jpg,image/jpeg,image/png]',
        ];
    }

    public static function fromArray(array $data): self
    {
        $dto = new self();
        
        // 使用 ?? null 確保即使前端沒傳該 key 也不會出錯
        $dto->title      = $data['title'] ?? null;
        $dto->authorName = $data['author_name'] ?? null;
        
        // 數字型態額外判斷，避免空字串或 null 轉成 0
        $dto->year       = (isset($data['year']) && $data['year'] !== '') ? (int)$data['year'] : null;
        $dto->price      = (isset($data['price']) && $data['price'] !== '') ? (float)$data['price'] : null;
        
        $dto->image_url  = $data['image_url'] ?? null;

        return $dto;
    }

    /**
     * 轉換成陣列，並選擇性過濾掉 null 值
     * 這樣在 Model->update($id, $data) 時，就不會把舊資料覆蓋成空
     */
    public function toArray(bool $filterEmpty=true): array
    {
        $data = [
            'title'       => $this->title,
            'author_name' => $this->authorName, // 建議跟資料庫欄位一致
            'year'        => $this->year,
            'price'       => $this->price,
            'image_url'   => $this->image_url,
        ];

        if($filterEmpty) {
            // 過濾掉所有 null 的值，只留下有修改的
            return array_filter($data, fn($value) => $value !== null);
        }

        return $data;
    }
}