<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiJsonResponse extends JsonResponse
{
    /**
     * ApiJsonResponse constructor.
     *
     * @param array $data
     */
    public function __construct($data = [], int $status = 200, ?string $message = null, array $headers = [], int $options = 0)
    {
        $responseData = [
            'data' => $data,
            'status' => $status,
            'message' => $message,
        ];
        parent::__construct($responseData, $status, $headers, $options);
    }
}
