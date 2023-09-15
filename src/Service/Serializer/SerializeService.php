<?php

namespace App\Service\Serializer;

use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class SerializeService
{
    public function __construct(
        protected readonly SerializerInterface $serializer
    ) {
    }

    public function responseWithGroup(mixed $data, array $group): string
    {
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups($group)
            ->withCircularReferenceHandler(function ($object) use ($group) {
                return $object->getId();
            })
            ->toArray();

        return $this->serializer->serialize($data, 'json', $context);
    }
}