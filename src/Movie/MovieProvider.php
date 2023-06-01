<?php

namespace App\Movie;

use App\Entity\Movie;
use App\Repository\MovieRepository;

class MovieProvider
{
    public function __construct(
        private MovieRepository $repository,
        private OmdbConsumer $consumer,
        private OmdbMovieTransformer $transformer,
        private GenreProvider $genreProvider,
    ) {}

    public function getMovie(OMDbSearchType $type, string $value): Movie
    {
        $data = $this->consumer->fetchMovie($type, $value);

        if ($movie = $this->repository->findOneBy(['title' => $data['Title']])) {
            return $movie;
        }

        $movie = $this->transformer->transform($data);
        foreach ($this->genreProvider->getGenresFromString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->repository->save($movie, true);

        return $movie;
    }
}
