<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiJsonResponse extends JsonResponse
{
    /**
     * ApiJsonResponse constructor.
     *
     * @param array<mixed, mixed>   $data
     * @param array<string, string> $headers
     */
    public function __construct(array $data = [], int $status = 200, ?string $message = null, array $headers = [])
    {
        $responseData = [
            'data' => $data,
            'status' => $status,
            'message' => $message,
        ];
        parent::__construct($responseData, $status, $headers);
    }
}
