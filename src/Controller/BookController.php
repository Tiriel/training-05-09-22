<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Security\Voter\BookVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function details(Book $book): Response
    {
        $this->denyAccessUnlessGranted(BookVoter::VIEW, $book);

        return $this->render('book/index.html.twig', [
            'controller_name' => $book->getId(),
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, BookRepository $repository)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($book);
            $book->setAddedBy($this->getUser());
            $repository->add($book, true);

            return $this->redirectToRoute('app_book_index');
        }

        return $this->renderForm('book/new.html.twig', [
            'form' => $form,
        ]);
    }
}
