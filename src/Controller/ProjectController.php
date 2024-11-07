<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Security\Voter\CreatedVoter;
use App\Security\Voter\EditVoter;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/show/{id}', name: 'show', methods: [Request::METHOD_GET])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/new', name: 'new', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[Route('/{id}/edit', name: 'edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        Project $project = null
    ): Response
    {
        if($project instanceof Project )
        {
            $this->denyAccessUnlessGranted(EditVoter::EDIT, $project);
        }

        $project = $project ?? new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project->setCreatedBy($this->getUser());
            $project->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($project);
            $entityManager->flush();
            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId(),
            ]);
        }
        return $this->render('project/edit.html.twig', [
            'form' => $form,
            'project' => $project,
        ]);
    }
}
