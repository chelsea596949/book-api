<?php
if(!function_exists('api_success')) {
    function api_success(string $message='OK', $data=null, array $meta=[]): array
    {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ];
    }
}

if(!function_exists('api_error')) {
    function api_error(string $message='Error', array $errors=[], int $code=400): array
    {
        return [
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
            'code' => $code,
        ];
    }
}

if(!function_exists('api_response')) {
    function api_response($response, array $payload)
    {
        $statusCode = $payload['code'] ?? 200;
        unset($payload['code']);

        return $response->setStatusCode($statusCode)
                        ->setJSON($payload);
    }
}

if(!function_exists('api_pagination')) {
    function api_pagination($pager, int $perPage): array
    {
        return [
            'page'        => $pager->getCurrentPage(),
            'perPage'     => $perPage,
            'total'       => $pager->getTotal(),
            'lastPage'    => $pager->getPageCount(),
            'hasNextPage' => $pager->hasMore(),
            'hasPrevPage' => $pager->getCurrentPage() > 1,
        ];
    }
}