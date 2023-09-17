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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/tournaments')]
class TournamentController extends AbstractController
{
    public function __construct(
        private readonly TournamentRepository $tournamentRepository,
        private readonly EntityManagerInterface $em,
        private readonly RequestService $requestService,
        private readonly SerializerInterface $serializer,
        private readonly TournamentServiceFactory $serviceFactory
    ) {
    }

    #[Route('/', name: 'app_tournaments', methods: 'GET')]
    public function index(): Response
    {
        $tournaments = $this->tournamentRepository->findAll();

        return new SuccessResponse($this->serializer->serialize($tournaments , 'json'));
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

        return new CreatedResponse($this->serializer->serialize($tournament , 'json'), 201);
    }

    #[Route('/{id}', name: 'app_tournament_show', methods: 'GET')]
    public function show(Tournament $tournament): Response
    {
        return new SuccessResponse($this->serializer->serialize($tournament , 'json'));
    }

    #[Route('/{id}/games', name: 'app_tournament_show_games', methods: 'GET')]
    public function showTournamentGames(Tournament $tournament): Response
    {
        return new SuccessResponse($this->serializer->serialize($tournament , 'json', ['with' => ['Games']]));
    }
}
