<?php

namespace App\Notifier\Factory;

use App\Notifier\Notifications\SlackNotification;
use Symfony\Component\Notifier\Recipient\Recipient;

class SlackNotificationFactory implements NotificationFactoryInterface, IterableFactoryInterface
{

    public function getNotification(string $channel)
    {
        return new SlackNotification('');
    }

    public function getDefaultIndex(): string
    {
        return 'slack';
    }
}