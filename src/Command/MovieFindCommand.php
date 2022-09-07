<?php

namespace App\Command;

use App\Consumer\OMDbApiConsumer;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsCommand(
    name: 'app:movie:find',
    description: 'Find a movie on OMDbApi by title or Imdb ID.',
)]
class MovieFindCommand extends Command
{
    public function __construct(
        private MovieRepository      $repository,
        private OMDbApiConsumer      $consumer,
        private OmdbMovieTransformer $transformer,
        string                       $name = null
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('value', InputArgument::OPTIONAL, 'The id or title of the movie you are searching for.')
            ->addArgument('type', InputArgument::OPTIONAL, 'Your type of search (id or title).');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if (!$value = $input->getArgument('value')) {
            $value = $io->ask('What is the title or id of the movie you\'re searching for?');
        }

        $type = strtolower($input->getArgument('type'));
        while (!in_array($type, ['title', 'id'])) {
            $type = strtolower($io->ask('What type of data are your searching on? (id or title)'));
        }

        $io->title('Your search :');
        $io->text(sprintf("A movie with %s \"%s\"", $type, $value));

        $io->text('Searching on OMDb API.');
        try {
            $movie = $this->transformer->transform(
                $this->consumer->fetchOmdbMovie(substr($type, 0, 1), $value)
            );
        } catch (NotFoundHttpException $e) {
            $io->error('No movie found on OMDb API.');
            return Command::FAILURE;
        }

        $property = $type === 'title' ? 'title' : 'imdbId';
        if (!$movieEntity = $this->repository->findOneBy([$property => $movie->getTitle()])) {
            $io->note('Not found in database. Saving.');
            $this->repository->add($movie, true);
        }

        $id = $movie->getId() ?? $movieEntity->getId();
        
        $io->section('Result :');
        $io->table(['id', 'imdbId', 'Title', 'Rated'], [
            [$id, $movie->getImdbId(), $movie->getTitle(), $movie->getRated()],
        ]);

        $io->success('Movie successfully found!');

        return Command::SUCCESS;
    }
}
