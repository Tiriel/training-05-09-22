<?php

namespace App\Controller;

use App\Consumer\OMDbApiConsumer;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie', name: 'app_movie_')]
class MovieController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(MovieRepository $repository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findAll(),
        ]);
    }

    #[Route('/{!id<\d+>?1}', name: 'details')]
    public function details(int $id, MovieRepository $repository): Response
    {
        return $this->render('movie/details.html.twig', [
            'movie' => $repository->find($id),
        ]);
    }

    #[Route('/omdb/{title}', name: 'omdb')]
    public function omdb(string $title, OMDbApiConsumer $consumer): Response
    {
        $movie = $consumer->fetchOmdbMovie($title);
        dd($movie);

        return $this->render('movie/index.html.twig');
    }
}
