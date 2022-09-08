<?php

namespace App\EventSubscriber;

use App\Event\MovieEvent;
use App\Notifier\MovieNotifier;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MovieSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserRepository $repository, private MovieNotifier $notifier)
    {
    }

    public function onMovieUnderage(MovieEvent $event): void
    {
        $movie = $event->getMovie();
        $admins = $this->repository->findAdmins();

        dump($admins, $movie);

        //foreach ($admins as $admin) {
        //    $this->notifier->notify($admin);
        //}
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MovieEvent::UNDERAGE => 'onMovieUnderage',
        ];
    }
}
