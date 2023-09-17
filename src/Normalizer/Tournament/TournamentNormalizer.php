<?php

namespace App\Normalizer\Tournament;

use App\Entity\Tournament;
use App\Normalizer\WithTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @method  getSupportedTypes(?string $format)
 */
class TournamentNormalizer  implements NormalizerInterface
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
        return $data instanceof Tournament;
    }

    private function withGames(mixed $object, array &$data): void
    {
        if ($object->getGames()->count() > 0) {
            $games = [];

            foreach ($object->getGames() as $game) {
                $games[] = [
                    'id' => $game->getId(),
                ];
            }

            $data['games'] = $games;
        }
    }
    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method  getSupportedTypes(?string $format)
    }
}