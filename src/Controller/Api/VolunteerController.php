<?php

namespace App\Controller\Api;

use App\Repository\VolunteerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/volunteers', name: 'app_volunteers_api_', format: 'json' )]
class VolunteerController extends AbstractController
{
    public function __construct(
        private VolunteerRepository $volunteerRepository
    )
    {
    }

    #[Route('/', name: 'list', methods: [Request::METHOD_GET])]
    public function list(): JsonResponse
    {
        return $this->json(
            $this->volunteerRepository->findAll(),
        );
    }
}
