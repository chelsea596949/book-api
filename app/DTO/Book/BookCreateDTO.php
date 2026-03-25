<?php
namespace App\DTO\Book;

class BookCreateDTO
{
    public string $title;
    public string $authorName;
    public int $year;
    public float $price;

    public static function rules(): array
    {
        return [
            'title' => 'required|string|max_length[255]|alpha_numeric_punct',
            'author_name' => 'required|string|max_length[255]|regex_match[/^[\p{Han}a-zA-Z0-9\s\.\-\_]+$/u]',
            'year' => 'required|integer|greater_than_equal_to[2000]|less_than_equal_to['.date('Y').']',
            'price' => 'required|numeric|greater_than_equal_to[0]',
        ];
    }

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->title = $data['title'];
        $dto->authorName = $data['author_name'];
        $dto->year = (int)$data['year'];
        $dto->price = (float)$data['price'];

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'authorName' => $this->authorName,
            'year' => $this->year,
            'price' => $this->price,
        ];
    }
}