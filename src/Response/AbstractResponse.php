<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractResponse extends JsonResponse
{
    public function __construct($data, $status = 200, $headers = [], $options = 0)
    {
        $formattedData = [
            'code' => $status,
            'data' => $data
        ];

        parent::__construct($formattedData, $status, $headers, $options);
    }
}