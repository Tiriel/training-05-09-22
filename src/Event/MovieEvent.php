<?php

namespace App\Event;

use App\Entity\Movie;
use Symfony\Contracts\EventDispatcher\Event;

class MovieEvent extends Event
{
    public const UNDERAGE = 'movie.underage';
    public const ORDER = 'movie.order';

    public function __construct(private Movie $movie)
    {
    }

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function setMovie(Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}