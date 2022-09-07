<?php

namespace App\Notifier\Factory;

use App\Notifier\Notifications\DiscordNotification;
use Symfony\Component\Notifier\Recipient\Recipient;

class DiscordNotificationFactory implements NotificationFactoryInterface, IterableFactoryInterface
{

    public function getNotification(string $channel)
    {
        return new DiscordNotification('');
    }

    public function getDefaultIndex(): string
    {
        return 'discord';
    }
}