<?php

namespace App\Consumer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OMDbApiConsumer
{
    public function __construct(private HttpClientInterface $omdbClient)
    {
    }

    public function fetchOmdbMovie(string $title): array
    {
        $data = $this->omdbClient->request(
            Request::METHOD_GET,
            '',
            ['query' => ['t' => $title]]
        )->toArray();

        if (\array_key_exists('Response', $data) && $data['Response'] == 'False') {
            throw new NotFoundHttpException('Movie not found.');
        }

        return $data;
    }
}