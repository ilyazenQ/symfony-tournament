<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class CreatedResponse extends JsonResponse
{
    public function __construct($data, $status = 201, $headers = [], $options = 0)
    {
        $formattedData = [
            'status' => 'Created',
            'data' => $data
        ];

        parent::__construct($formattedData, $status, $headers, $options);
    }
}
