<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table            = 'books';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = ['title', 'author_id', 'year', 'slug', 'price', 'image_url'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function withAuthorInfo(): self
    {
        return $this->select('books.*, authors.name as author_name')
                    ->join('authors', 'books.author_id = authors.id');
    }

    public function filterAuthorName(?string $name): self
    {
        if($name) {
            $this->where('authors.name LIKE', '%' . $name . '%');
        }

        return $this;
    }

    public function filterTitle(?string $title): self
    {
        if($title) {
            $this->like('books.title', $title);
        }

        return $this;
    }

    public function filterSearch(?string $search): self
    {
        if($search) {
            $this->groupStart()
                 ->like('books.title', $search)
                 ->orLike('authors.name', $search)
                 ->groupEnd();
        }

        return $this;
    }

    public function filterSlug(?string $slug): self
    {
        if($slug) {
            $this->like('books.slug', $slug);
        }

        return $this;
    }

    public function sortBy(?string $sort, ?string $direction='ASC'): self
    {
        if($sort) {
            $this->orderBy($sort, strtoupper($direction));
        }
        return $this;
    }
}
