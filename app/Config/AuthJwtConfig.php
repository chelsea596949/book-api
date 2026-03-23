<?php
namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class AuthJwtConfig extends BaseConfig
{
    public string $key;
    public string $algo = 'HS256';
    public int $ttl;

    public function __construct()
    {
        parent::__construct();

        $this->key = env('jwt.key', 'default-key');
        $this->ttl = (int)env('jwt.ttl', 3600);
    }
}