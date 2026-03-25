<?php
namespace App\DTO\Book;

class BookResponseDTO
{
    public bool $error;
    public ?string $message;
    public ?int $code;
    public $data;
    public array $meta;

    public function __construct(
        bool $error = false,
        $data = null,
        array $meta = [],
        ?string $message = null,
        ?int $code = null
    ) {
        $this->error = $error;
        $this->data = $data;
        $this->meta = $meta;
        $this->message = $message;
        $this->code = $code;
    }

    // public function toArray(): array
    // {
    //     return [
    //         'error' => $this->error,
    //         'message' => $this->message,
    //         'code' => $this->code,
    //         'data' => $this->data,
    //         'meta' => $this->meta,
    //     ];
    // }

    // 轉成原本 helper 格式
    public function toApiFormat(): array
    {
        if ($this->error) {
            return api_error(
                $this->message ?? 'Error',
                [],
                $this->code ?? 400
            );
        }

        return api_success(
            $this->message ?? 'OK',
            $this->data,
            $this->meta
        );
    }
}