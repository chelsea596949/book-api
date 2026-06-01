<?php
namespace App\DTO\Book;

class BookQueryDTO
{
    public ?string $authorName;
    public ?string $slug;
    public ?string $title;
    public ?string $search;
    public ?int $year;
    public string $sort;
    public string $direction;
    public ?int $page;
    public ?int $perPage;

    public function __construct(array $data)
    {
        $this->authorName = $data['authorName'] ?? null;
        $this->slug = $data['slug'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->search = $data['search'] ?? null;
        $this->year = isset($data['year']) && $data['year'] !== '' ? (int)$data['year'] : null;
        $this->sort = $data['sort'] ?? 'id';
        $this->direction = $data['direction'] ?? 'asc';
        $this->page = isset($data['page']) ? (int)$data['page'] : null;
        $this->perPage = isset($data['perPage']) ? (int)$data['perPage'] : null;
    }
}