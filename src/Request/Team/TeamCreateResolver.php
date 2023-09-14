<?php

namespace App\Request\Team;

use App\Response\Error\ValidationErrorResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class TeamCreateResolver implements ValueResolverInterface
{

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return TeamCreateRequest::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $data = json_decode($request->getContent(), true);

        $teamCreateRequest = $this->serializer->denormalize($data, TeamCreateRequest::class);

        $errors = $this->validator->validate($teamCreateRequest);

        if (count($errors) > 0) {
            return new ValidationErrorResponse($errors);
        }

        yield $teamCreateRequest;
    }
}