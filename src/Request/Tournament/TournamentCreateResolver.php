<?php

namespace App\Request\Tournament;

use App\Request\Team\TeamCreateRequest;
use App\Response\Error\ValidationErrorResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TournamentCreateResolver implements ValueResolverInterface
{

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return TournamentCreateRequest::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $data = json_decode($request->getContent(), true);

        $createRequest = $this->serializer->denormalize($data, TeamCreateRequest::class);

        $errors = $this->validator->validate($createRequest);

        if (count($errors) > 0) {
            return new ValidationErrorResponse($errors);
        }

        yield $createRequest;
    }
}


