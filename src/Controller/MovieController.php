<?php

namespace App\Controller;

use App\Event\MovieEvent;
use App\Provider\MovieProvider;
use App\Repository\MovieRepository;
use App\Security\Voter\MovieRatedVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

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
    public function details(int $id, MovieRepository $repository, EventDispatcherInterface $dispatcher): Response
    {
        $movie = $repository->find($id);

        if ($movie) {
            try {
                $this->denyAccessUnlessGranted(MovieRatedVoter::VIEW, $movie);
            } catch (AccessDeniedException $e) {
                $dispatcher->dispatch(new MovieEvent($movie), MovieEvent::UNDERAGE);
                throw $e;
            }
        }

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/omdb/{title}', name: 'omdb')]
    public function omdb(string $title, MovieProvider $provider, EventDispatcherInterface $dispatcher): Response
    {
        $movie = $provider->getMovieByTitle($title);

        if ($movie) {
            try {
                $this->denyAccessUnlessGranted(MovieRatedVoter::VIEW, $movie);
            } catch (AccessDeniedException $e) {
                $dispatcher->dispatch(new MovieEvent($movie), MovieEvent::UNDERAGE);
                throw $e;
            }
        }

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }
}
