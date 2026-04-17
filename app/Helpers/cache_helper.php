<?php
if(!function_exists('safe_get_cache')) {
    function safe_get_cache($key) {
        try {
            // 先嘗試抓取快取服務 (這步最容易炸)
            $cache = \Config\Services::cache();
            return $cache->get($key);
        } catch (\Throwable $e) { // 使用 Throwable 可以捕捉更多型別的錯誤
            log_message('error', 'Redis 故障，切換備援: ' . $e->getMessage());

            // 強制建立一個全新的「檔案」快取實例，不依賴預設的 Service
            $config = new \Config\Cache();
            $fileHandler = new \CodeIgniter\Cache\Handlers\FileHandler($config);
            $fileHandler->initialize();

            return $fileHandler->get($key);
        }
    }
}

if(!function_exists('safe_save_cache')) {
    function safe_save_cache($key, $value, $ttl=60) {
        try {
            // 先嘗試抓取快取服務 (這步最容易炸)
            $cache = \Config\Services::cache();
            return $cache->save($key, $value, $ttl);
        } catch (\Throwable $e) { // 使用 Throwable 可以捕捉更多型別的錯誤
            log_message('error', 'Redis 故障，切換備援: ' . $e->getMessage());

            // 強制建立一個全新的「檔案」快取實例，不依賴預設的 Service
            $config = new \Config\Cache();
            $fileHandler = new \CodeIgniter\Cache\Handlers\FileHandler($config);
            $fileHandler->initialize();

            return $fileHandler->save($key, $value, $ttl);
        }
    }
}


if(!function_exists('safe_clean_cache')) {
    function safe_clean_cache() {
        try {
            // 先嘗試抓取快取服務 (這步最容易炸)
            $cache = \Config\Services::cache();
            return $cache->clean();
        } catch (\Throwable $e) { // 使用 Throwable 可以捕捉更多型別的錯誤
            log_message('error', 'Redis 故障，切換備援: ' . $e->getMessage());

            // 強制建立一個全新的「檔案」快取實例，不依賴預設的 Service
            $config = new \Config\Cache();
            $fileHandler = new \CodeIgniter\Cache\Handlers\FileHandler($config);
            $fileHandler->initialize();

            return $fileHandler->clean();
        }
    }
}