<?php

namespace App\Notifier\Factory;

use App\Notifier\Notifications\DiscordNotification;
use Symfony\Component\Notifier\Notification\Notification;

class DiscordNotificationFactory implements NotificationFactoryInterface, IterableFactoryInterface
{

    public function createNotification(string $subject): Notification
    {
        return (new DiscordNotification())->subject($subject);
    }

    public static function getDefaultIndex(): string
    {
        return 'discord';
    }
}
