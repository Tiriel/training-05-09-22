<?php

namespace App\EventSubscriber;

use App\Entity\Book;
use App\Event\BookEvent;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewBookSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserRepository $repository, private MailerInterface $mailer)
    {
    }

    public function onNewBookEvent(BookEvent $event): void
    {
        $book = $event->getBook();
        $users = $this->repository->findBy([]);

        $mails = [];
        foreach ($users as $user) {
            $mails[] = $user->getEmail();
        }
        $message = (new Email())
            ->subject('New book from your preferred author!')
            ->text(sprintf("New book from %s : \"%s\"", $book->getAuthor(), $book->getTitle()))
            ->to(...$mails)
        ;
        $this->mailer->send($message);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BookEvent::NEW_BOOK => 'onNewBookEvent',
        ];
    }
}
