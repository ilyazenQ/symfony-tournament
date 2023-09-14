<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class SuccessResponse extends JsonResponse
{
    public function __construct($data, $status = 200, $headers = [], $options = 0)
    {
        $formattedData = [
            'status' => 'OK',
            'data' => $data
        ];

        parent::__construct($formattedData, $status, $headers, $options);
    }
}