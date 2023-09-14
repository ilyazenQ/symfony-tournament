<?php

namespace App\Action\Tournament;

use App\Entity\Tournament;
use App\Request\RequestInterface;
use App\Service\Tournament\TournamentRoundService;
use Doctrine\ORM\EntityManagerInterface;

class CreateTournamentAction
{
    public function execute(
        RequestInterface $request,
        EntityManagerInterface $em,
        TournamentRoundService $roundService
    ): Tournament {
        return $roundService->process($request, $em);
    }
}