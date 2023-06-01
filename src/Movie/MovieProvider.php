<?php

namespace App\Movie;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider
{
    private ?SymfonyStyle $io = null;

    public function __construct(
        private MovieRepository $repository,
        private OmdbConsumer $consumer,
        private OmdbMovieTransformer $transformer,
        private GenreProvider $genreProvider,
    ) {}

    public function getMovie(OMDbSearchType $type, string $value): Movie
    {
        $this->sendIo('text', 'Fetching informations from OMDb API');
        $data = $this->consumer->fetchMovie($type, $value);
        $this->sendIo('text', 'Movie found on OMDb API');

        if ($movie = $this->repository->findOneBy(['title' => $data['Title']])) {
            $this->sendIo('note', 'Movie already in database!');
            return $movie;
        }

        $movie = $this->transformer->transform($data);
        foreach ($this->genreProvider->getGenresFromString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->sendIo('text', 'Saving movie in database');
        $this->repository->save($movie, true);

        return $movie;
    }

    public function setIo(SymfonyStyle $io): void
    {
        $this->io = $io;
    }

    public function sendIo(string $type, string $message)
    {
        if ($this->io && method_exists($type, $this->io)) {
            $this->io->$type($message);
        }
    }
}
