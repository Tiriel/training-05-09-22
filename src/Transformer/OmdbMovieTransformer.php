<?php

namespace App\Transformer;

use App\Entity\Genre;
use App\Entity\Movie;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbMovieTransformer implements DataTransformerInterface
{

    public function transform(mixed $value): Movie
    {
        if (!\is_array($value)) {
            throw new \RuntimeException('Transformation needs an array as entry value.');
        }

        $date = $value['Released'] === 'N/A' ? $value['Year'] : $value['Released'];

        $movie = (new Movie())
            ->setTitle($value['Title'])
            ->setPoster($value['Poster'])
            ->setCountry($value['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPrice(5.0)
        ;

        $genreNames = explode(', ', $value['Genre']);
        foreach ($genreNames as $genreName) {
            $genre = (new Genre())->setName($genreName);
            $movie->addGenre($genre);
        }

        return $movie;
    }

    public function reverseTransform(mixed $value)
    {
        throw new \RuntimeException('method not implemented.');
    }
}