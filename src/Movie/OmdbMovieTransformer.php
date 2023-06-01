<?php

namespace App\Movie;

use App\Entity\Movie;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbMovieTransformer implements DataTransformerInterface
{
    public function __construct(private OmdbGenreTransformer $genreTransformer) {}

    public function transform(mixed $value)
    {
        $date = 'N/A' === $value['Released'] ? '01-01-'.$value['Year'] : $value['Released'];

        $movie = (new Movie())
            ->setTitle($value['Title'])
            ->setPoster($value['Poster'])
            ->setCountry($value['Country'])
            ->setPlot($value['Plot'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPrice(5)
            ;

        foreach (explode(', ', $value['Genre']) as $genreName) {
            $movie->addGenre($this->genreTransformer->transform($genreName));
        }

        return $movie;
    }

    public function reverseTransform(mixed $value)
    {
        // TODO: Implement reverseTransform() method.
    }
}
