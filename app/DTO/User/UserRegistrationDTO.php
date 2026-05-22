<?php
namespace App\DTO\User;

class UserRegistrationDTO
{
    public string $uid;
    public string $password;
    public string $name;

    public static function rules(): array
    {
        return [
            'uid' => 'required|string|min_length[3]|max_length[50]|regex_match[/^[a-zA-Z0-9_]+$/]|is_unique[users.uid]',
            'password' => 'required|string|min_length[6]|max_length[255]',
            'name' => 'required|string|min_length[1]|max_length[255]|regex_match[/^[\p{Han}a-zA-Z0-9\s\.\-\_]+$/u]',
        ];
    }

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->uid = trim($data['uid'] ?? '');
        $dto->password = $data['password'] ?? '';
        $dto->name = trim($data['name'] ?? '');

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'uid' => $this->uid,
            'password' => $this->password,
            'name' => $this->name,
        ];
    }
}
