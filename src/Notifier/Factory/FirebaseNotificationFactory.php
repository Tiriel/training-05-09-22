<?php

namespace App\Notifier\Factory;

use App\Notifier\Notifications\FirebaseNotification;
use Symfony\Component\Notifier\Recipient\Recipient;

class FirebaseNotificationFactory implements NotificationFactoryInterface, IterableFactoryInterface
{
    public function getNotification(string $channel)
    {
        return new FirebaseNotification('');
    }

    public function getDefaultIndex(): string
    {
        return 'firebase';
    }
}