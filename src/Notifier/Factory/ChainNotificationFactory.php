<?php

namespace App\Notifier\Factory;

use Symfony\Component\Notifier\Recipient\Recipient;

class ChainNotificationFactory implements NotificationFactoryInterface
{
    public function __construct(private iterable $factories)
    {
        $this->factories = $factories instanceof \Traversable ? iterator_to_array($factories) : $this->factories;
    }

    public function getNotification(string $channel)
    {
        return $this->factories[$channel];
    }
}