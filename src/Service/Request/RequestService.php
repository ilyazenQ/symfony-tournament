<?php

namespace App\Service\Request;

use App\Request\Tournament\TournamentCreateRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestService
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function processRequest(Request $request, string $resolveClass): mixed
    {
        $request = $this->serializer->deserialize(
            $request->getContent(),
            $resolveClass,
            'json'
        );

        $errors = $this->validator->validate($request);

        if (count($errors) > 0) {
            throw new HttpException(422, sprintf(...$errors));
        }

        return $request;
    }
}