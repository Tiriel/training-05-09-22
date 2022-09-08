<?php

namespace App\Controller;

use App\Provider\MovieProvider;
use App\Repository\MovieRepository;
use App\Security\Voter\MovieRatedVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
        $movie = $repository->find($id);

        if ($movie) {
            $this->denyAccessUnlessGranted(MovieRatedVoter::VIEW, $movie);
        }

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/omdb/{title}', name: 'omdb')]
    public function omdb(string $title, MovieProvider $provider): Response
    {
        $movie = $provider->getMovieByTitle($title);

        if ($movie) {
            $this->denyAccessUnlessGranted(MovieRatedVoter::VIEW, $movie);
        }

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }
}
