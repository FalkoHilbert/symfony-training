<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects', name: 'app_project_')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'list', methods: [Request::METHOD_GET])]
    public function list(ProjectRepository $projectRepository): Response
    {
        return $this->render('project/list.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: [Request::METHOD_GET])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }
}
