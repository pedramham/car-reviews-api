<?php

namespace App\Controller\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    public function __construct(
        $data = null,
        string $message = null,
        bool $success = true,
        int|string $status = 200,
        array $headers = [],
        bool $json = false,
        bool $serialize = false,
    ) {
        $result = [
            'success' => $success,
            'message' => $message,
            'data' => ($serialize) ? json_decode($data) : $data,
        ];

        $this->statusCode = $status;
        if ($this->isInvalid()) {
            $status = self::HTTP_INTERNAL_SERVER_ERROR;
        }

        parent::__construct($result, $status, $headers, $json);
    }
}
