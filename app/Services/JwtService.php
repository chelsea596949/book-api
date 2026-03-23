<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Config\AuthJwtConfig as JwtConfig;

class JwtService
{
    protected JwtConfig $config;

    public function __construct(JwtConfig $config)
    {
        $this->config = $config;
    }

    public function generate(array $payload): string
    {
        $time = time();

        $data = [
            'iat' => $time,
            'exp' => $time + $this->config->ttl,
            'data' => $payload
        ];

        return JWT::encode($data, $this->config->key, $this->config->algo);
    }

    public function verify(string $token): object
    {
        return JWT::decode(
            $token,
            new Key($this->config->key, $this->config->algo)
        );
    }
}