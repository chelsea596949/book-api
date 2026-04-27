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
    
    /**
     * 修改後的回傳格式：包含 token, iat, exp 的陣列
     */
    public function generate(array $payload): array
    {
        $iat = time();
        $exp = $iat + $this->config->ttl;

        $data = [
            'iat' => $iat,
            'exp' => $exp,
            'data' => $payload
        ];

        $token = JWT::encode($data, $this->config->key, $this->config->algo);

        return [
            'token' => $token,
            'iat'   => $iat,
            'exp'   => $exp
        ];
    }

    public function verify(string $token): object
    {
        return JWT::decode(
            $token,
            new Key($this->config->key, $this->config->algo)
        );
    }
}