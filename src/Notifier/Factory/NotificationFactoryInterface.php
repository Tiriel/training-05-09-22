<?php

namespace App\Notifier\Factory;

use Symfony\Component\Notifier\Recipient\Recipient;

interface NotificationFactoryInterface
{
    public function getNotification(string $channel);
}