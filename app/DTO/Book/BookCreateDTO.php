<?php
namespace App\DTO\Book;

class BookCreateDTO
{
    public string $title;
    public string $authorName;
    public int $year;
    public float $price;
    public ?string $image_url;

    public static function rules(): array
    {
        return [
            'title' => 'required|string|max_length[255]|alpha_numeric_punct',
            'author_name' => 'required|string|max_length[255]|regex_match[/^[\p{Han}a-zA-Z0-9\s\.\-\_]+$/u]',
            'year' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to['.date('Y').']',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'book_image' => 'uploaded[book_image]|max_size[book_image,2048]|is_image[book_image]|mime_in[book_image,image/jpg,image/jpeg,image/png]',// 加入圖片驗證：只能是圖片、最大 2MB
        ];
    }

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->title = $data['title'];
        $dto->authorName = $data['author_name'];
        $dto->year = (int)$data['year'];
        $dto->price = (float)$data['price'];
        $dto->image_url = $data['image_url'] ?? null;

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'authorName' => $this->authorName,
            'year' => $this->year,
            'price' => $this->price,
            'image_url' => $this->image_url,
        ];
    }
}