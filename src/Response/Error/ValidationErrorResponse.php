<?php

namespace App\Response\Error;

use Symfony\Component\HttpFoundation\JsonResponse;

class ValidationErrorResponse extends JsonResponse
{
    public function __construct($data = null, $status = 422, $headers = [], $options = 0)
    {
        $formattedData = [
            'status' => 'Validation Errors',
            'data' => $data
        ];

        parent::__construct($formattedData, $status, $headers, $options);
    }
}