<?php

namespace App\EventSubscriber;

use App\Movie\Event\MovieEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MovieSubscriber implements EventSubscriberInterface
{
    public function onMovieEvent(MovieEvent $event)
    {
        dump('Movie viewing! - '.$event->getMovie()->getTitle());
    }

    public function onNewMovie(MovieEvent $event)
    {
        dump('New Movie! - '.$event->getMovie()->getTitle());
    }

    public static function getSubscribedEvents()
    {
        return [
            MovieEvent::VIEWING => 'onMovieEvent',
            MovieEvent::NEW => 'onNewMovie',
        ];
    }
}
