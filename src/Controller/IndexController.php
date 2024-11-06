<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_root', methods: ['GET'])]
    #[Route('/index', name: 'app_index', methods: ['GET'])]
    #[Template('main/index.html.twig')]
    public function index(): void
    { }

    #[Route('/contact', name: 'app_contact', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[Template('main/contact.html.twig')]
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->addFlash('success', 'Your message has been sent!');
            dump($data);
        }
        return $this->render('main/contact.html.twig', [
            'form' => $form
        ]);

    }
}
