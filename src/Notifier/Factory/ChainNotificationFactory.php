<?php

namespace App\Notifier\Factory;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Notifier\Notification\Notification;

class ChainNotificationFactory implements NotificationFactoryInterface
{
    /** @var NotificationFactoryInterface[] */
    private iterable $factories;
    public function __construct(
        #[TaggedIterator('app.notification_factory', defaultIndexMethod: 'getDefaultIndex')]
        iterable $factories
    ) {
        $this->factories = $factories instanceof \Traversable ? iterator_to_array($factories) : $factories;
    }

    public function createNotification(string $subject, string $channel = 'slack'): Notification
    {
        return $this->factories[$channel]->createNotification($subject);
    }
}
