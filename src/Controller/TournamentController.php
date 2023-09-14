<?php

namespace App\Controller;

use App\Action\Tournament\CreateTournamentAction;
use App\Entity\Tournament;
use App\Repository\TournamentRepository;
use App\Request\Tournament\TournamentCreateRequest;
use App\Response\CreatedResponse;
use App\Response\SuccessResponse;
use App\Service\Serializer\SerializeService;
use App\Service\Tournament\TournamentServiceFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tournaments')]
class TournamentController extends AbstractController
{
    public function __construct(
        private readonly TournamentRepository $tournamentRepository,
        private readonly EntityManagerInterface $em,
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
    public function create(#[MapRequestPayload] TournamentCreateRequest $request): Response
    {
        $tournament = (new CreateTournamentAction())->execute(
            $request,
            $this->em,
            $this->serviceFactory->getTournamentRoundService()
        );

        return new CreatedResponse($this->serializerService->responseWithGroup($tournament, ['tournament_games']));
    }

    #[Route('/show/{id}', name: 'app_tournament_show')]
    public function show(Tournament $tournament): Response
    {
        return new SuccessResponse($this->serializerService->responseWithGroup($tournament, ['tournament_games']));
    }
}
