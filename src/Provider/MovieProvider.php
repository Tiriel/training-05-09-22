<?php

namespace App\Provider;

use App\Consumer\OMDbApiConsumer;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;
use Symfony\Component\HttpClient\HttpClient;

class MovieProvider
{
    public function __construct(
        private OMDbApiConsumer      $consumer,
        private OmdbMovieTransformer $transformer,
        private MovieRepository      $repository
    ) {}

    public function getMovieByTitle(string $title): Movie
    {
        $movie = $this->transformer->transform(
            $this->consumer->getMovieByTitle($title)
        );

        if ($movieEntity = $this->repository->findOneBy(['title' => $movie->getTitle()])) {
            return $movieEntity;
        }

        $this->repository->add($movie, true);

        return $movie;
    }
}