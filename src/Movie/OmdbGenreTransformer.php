<?php

namespace App\Movie;

use App\Entity\Genre;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbGenreTransformer implements DataTransformerInterface
{
    public function transform(mixed $value)
    {
        return (new Genre())->setName($value);
    }

    public function reverseTransform(mixed $value)
    {
        // TODO: Implement reverseTransform() method.
    }
}
