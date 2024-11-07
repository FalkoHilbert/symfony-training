<?php

namespace App\Controller\Api;

use App\Entity\Volunteer;
use App\Repository\VolunteerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/volunteers', name: 'app_volunteers_api_', format: 'json' )]
class VolunteerController extends AbstractController
{
    public function __construct(
        private VolunteerRepository $volunteerRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
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

    #[Route('/{id}', name: 'get', methods: [Request::METHOD_GET])]
    public function show(Volunteer $volunteer): JsonResponse
    {
        return $this->json($volunteer);
    }

    #[Route('/{id}', name: 'update', methods: [Request::METHOD_PUT])]
    public function update(
        Volunteer $volunteer,
        Request $request
    ): JsonResponse
    {
        $this->serializer->deserialize(
            $request->getContent(), Volunteer::class, 'json',
            [
                AbstractNormalizer::OBJECT_TO_POPULATE => $volunteer
            ]
        );
        $validationResult = $this->validator->validate($volunteer);
        if( count($validationResult) !== 0) {
            return $this->json($validationResult);
        }
        return $this->json($volunteer);

    }
}
