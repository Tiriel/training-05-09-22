<?php

namespace App\Notifier;

use App\Notifier\Factory\ChainNotificationFactory;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MovieNotifier
{
    public function __construct(private NotifierInterface $notifier, private ChainNotificationFactory $factory)
    {
    }

    public function notify($user)
    {
        $recipient = new Recipient($user->getPhone(), $user->getEmail());
        $notification = $this->factory->getNotification($user->getPreferredChannel());

        $this->notifier->send($notification, $recipient);
    }
}