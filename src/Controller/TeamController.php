<?php

namespace App\Controller;

use App\Action\Team\CreateTeamAction;
use App\Repository\TeamRepository;
use App\Request\Team\TeamCreateRequest;
use App\Response\CreatedResponse;
use App\Response\SuccessResponse;
use App\Service\Serializer\SerializeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/teams')]
class TeamController extends AbstractController
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly SerializeService $serializerService,
    ) {
    }

    #[Route('/', name: 'app_team', methods: 'GET')]
    public function index(): Response
    {
        $teams = $this->teamRepository->findAll();

        return new SuccessResponse($this->serializerService->responseWithGroup($teams, ['show_team']));
    }

    #[Route('/create', name: 'app_team_create', methods: ['POST'])]
    public function create(#[MapRequestPayload]TeamCreateRequest $request): Response
    {
        $team = (new CreateTeamAction())->execute($request, $this->teamRepository);

        return new CreatedResponse($this->serializerService->responseWithGroup($team, ['show_team']));
    }
}
