<?php

namespace App\Controller;

use App\Action\Team\CreateTeamAction;
use App\Repository\TeamRepository;
use App\Request\Team\TeamCreateRequest;
use App\Response\CreatedResponse;
use App\Response\SuccessResponse;
use App\Service\Request\RequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/teams')]
class TeamController extends AbstractController
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly RequestService $requestService,
        private readonly SerializerInterface $serializer,

    ) {
    }

    #[Route('/', name: 'app_team', methods: 'GET')]
    public function index(): Response
    {
        $teams = $this->teamRepository->findAll();

        return new SuccessResponse($this->serializer->serialize($teams , 'json'));
    }

    #[Route('/create', name: 'app_team_create', methods: ['POST'])]
    public function create(Request $request, CreateTeamAction $action): Response
    {
        $request = $this->requestService->processRequest($request, TeamCreateRequest::class);

        $team = $action->execute($request, $this->teamRepository);

        return new CreatedResponse($this->serializer->serialize($team , 'json'), 201);
    }
}
