<?php

namespace App\Controller;

use App\Action\Tournament\CreateTournamentAction;
use App\Entity\Tournament;
use App\Repository\TournamentRepository;
use App\Request\Tournament\TournamentCreateRequest;
use App\Response\CreatedResponse;
use App\Response\SuccessResponse;
use App\Service\Request\RequestService;
use App\Service\Serializer\SerializeService;
use App\Service\Tournament\TournamentServiceFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tournaments')]
class TournamentController extends AbstractController
{
    public function __construct(
        private readonly TournamentRepository $tournamentRepository,
        private readonly EntityManagerInterface $em,
        private readonly RequestService $requestService,
        private readonly SerializeService $serializerService,
        private readonly TournamentServiceFactory $serviceFactory
    ) {
    }

    #[Route('/', name: 'app_tournaments', methods: 'GET')]
    public function index(): Response
    {
        $tournaments = $this->tournamentRepository->findAll();

        return new SuccessResponse($this->serializerService->responseWithGroup($tournaments, ['show_tournaments']));
    }

    #[Route('/create', name: 'app_tournament_create', methods: ['POST'])]
    public function create(Request $request, CreateTournamentAction $action): Response
    {
        $request = $this->requestService->processRequest($request, TournamentCreateRequest::class);

        $tournament = $action->execute(
            $request,
            $this->em,
            $this->serviceFactory->getTournamentRoundService()
        );

        return new CreatedResponse($this->serializerService->responseWithGroup($tournament, ['show_tournaments']));
    }

    #[Route('/{id}', name: 'app_tournament_show')]
    public function show(Tournament $tournament): Response
    {
        return new SuccessResponse($this->serializerService->responseWithGroup($tournament, ['show_tournaments']));
    }

    #[Route('/{id}/games', name: 'app_tournament_show')]
    public function showTournamentGames(Tournament $tournament): Response
    {
        return new SuccessResponse(
            $this->serializerService->responseWithGroup($tournament->getGames(), ['show_tournaments'])
        );
    }
}
