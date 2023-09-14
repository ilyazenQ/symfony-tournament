<?php

namespace App\Service\Tournament;

class TournamentServiceFactory
{
    public function getTournamentRoundService(): TournamentRoundService
    {
        return new TournamentRoundService();
    }
}