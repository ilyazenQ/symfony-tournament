<?php

namespace App\Service\Tournament;

use App\Entity\Game;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Request\RequestInterface;
use Doctrine\ORM\EntityManagerInterface;
use Schedule;
use ScheduleBuilder;

class TournamentRoundService
{
    private function scheduleBuilder(array $teams): Schedule
    {
        $scheduleBuilder = new ScheduleBuilder($teams);
        $scheduleBuilder->doNotShuffle();
        return $scheduleBuilder->build();
    }

    private function resolveTeams(array $teams, EntityManagerInterface $em): array
    {
        return count($teams) === 0 ? $em->getRepository(Team::class)->findAll() : $em->getRepository(
            Team::class
        )->findBy(['id' => $teams]);
    }

    public function process(RequestInterface $request, EntityManagerInterface $em): Tournament
    {
        $teams = $this->resolveTeams($request->teams, $em);
        $schedule = $this->scheduleBuilder($teams);

        $tournament = $em->getRepository(Tournament::class)->createFromRequest($request);

        $this->resolveGames($schedule, $em, $tournament);

        return $tournament;
    }

    private function resolveGames(Schedule $schedule, EntityManagerInterface $em, Tournament $tournament): void
    {
        $daysCounter = 0;

        foreach ($schedule->full() as $game) {
            foreach ($game as $match) {
                if (is_null($match[0]) || is_null($match[1])) {
                    continue;
                }
                $gameDate = (new \DateTime())->modify("+$daysCounter days");

                $gameEntity = new Game();
                $gameEntity->setTeamOne($match[0]);
                $gameEntity->setTeamTwo($match[1]);
                $gameEntity->setTournament($tournament);
                $gameEntity->setDate($gameDate);

                $em->persist($gameEntity);
            }
            $daysCounter++;
            $em->flush();
        }
    }

}