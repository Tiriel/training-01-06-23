<?php

namespace App\Movie\Provider;

use App\Movie\Transformers\OmdbGenreTransformer;
use App\Repository\GenreRepository;

class GenreProvider
{
    public function __construct(
        private GenreRepository $repository,
        private OmdbGenreTransformer $transformer
    ) {}

    public function getGenresFromString(string $data): \Generator
    {
        foreach (explode(', ', $data) as $genreName) {
            $genre = $this->repository->findOneBy(['name' => $genreName])
                ?? $this->transformer->transform($genreName);

            yield $genre;
        }
    }
}
