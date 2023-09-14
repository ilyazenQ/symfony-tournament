<?php

namespace App\Request\Tournament;

use App\Request\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

class TournamentCreateRequest implements RequestInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 100)]
    public string $name;

    public array $teams = [];
}