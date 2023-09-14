<?php

namespace App\Action\Team;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Request\RequestInterface;

class CreateTeamAction
{
    public function execute(RequestInterface $request, TeamRepository $teamRepository): Team
    {
        return $teamRepository->createFromRequest($request);
    }
}