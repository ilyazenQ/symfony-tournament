<?php

namespace App\Normalizer;

trait WithTrait
{
    public function addRelations(mixed $object, mixed &$data, array $context): void
    {
        if(array_key_exists('with', $context)) {
            foreach ($context['with'] as $relation) {
                $functionName = 'with' . $relation;
                $this->$functionName($object, $data);
            }
        }
    }
}