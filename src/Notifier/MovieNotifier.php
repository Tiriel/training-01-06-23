<?php

namespace App\Notifier;

use App\Notifier\Factory\ChainNotificationFactory;
use App\Notifier\Factory\NotificationFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MovieNotifier
{
    public function __construct(
        private NotifierInterface $notifier,
        #[Autowire(service: ChainNotificationFactory::class)]
        private NotificationFactoryInterface $factory
    ) {}

    public function sendMovieNotification(string $message, string $userEmail, string $channel)
    {
        $this->notifier->send($this->factory->createNotification($message, $channel), new Recipient($userEmail));
    }
}
