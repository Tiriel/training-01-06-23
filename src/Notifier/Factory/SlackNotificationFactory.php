<?php

namespace App\Notifier\Factory;

use App\Notifier\Notifications\SlackNotification;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\Notifier\Notification\Notification;

class SlackNotificationFactory implements NotificationFactoryInterface, IterableFactoryInterface
{
    public function createNotification(string $subject): Notification
    {
        return (new SlackNotification())->subject($subject);
    }

    public static function getDefaultIndex(): string
    {
        return 'slack';
    }
}
