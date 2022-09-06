<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book', name: 'app_book_')]
class BookController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/{id<\d+>?1}', name: 'details')]
    public function details(int $id = 1): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => $id,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new()
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        dump($book);

        return $this->renderForm('book/new.html.twig', [
            'form' => $form,
        ]);
    }
}
