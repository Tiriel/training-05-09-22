<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'index',
        ]);
    }

    #[Route('/contact', name: 'app_default_contact')]
    public function contact(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'contact',
        ]);
    }
}
