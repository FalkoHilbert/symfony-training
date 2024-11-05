<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_root', methods: ['GET'])]
    #[Route('/index', name: 'app_index', methods: ['GET'])]
    #[Template('main/index.html.twig')]
    public function index(): void
    { }

    #[Route('/contact', name: 'app_contact', methods: ['GET'])]
    #[Template('main/contact.html.twig')]
    public function contact(): void
    { }
}
