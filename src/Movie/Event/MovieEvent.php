<?php

namespace App\Movie\Event;

use App\Entity\Movie;
use Symfony\Contracts\EventDispatcher\Event;

class MovieEvent extends Event
{
    public const VIEWING = 'movie.viewing';
    public const EDIT = 'movie.edit';
    public const NEW = 'movie.new';

    public function __construct(
        private Movie $movie
    ) {}

    public function getMovie(): Movie
    {
        return $this->movie;
    }
}
