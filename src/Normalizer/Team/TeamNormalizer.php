<?php

namespace App\Normalizer\Team;

use App\Entity\Team;
use App\Normalizer\WithTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TeamNormalizer implements NormalizerInterface
{
    use WithTrait;
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $data = [
            'id' => $object->getId(),
            'name' => $object->getName(),
        ];

        $this->addRelations($object, $data, $context);

        return $data;
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof Team;
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method  getSupportedTypes(?string $format)
    }
}