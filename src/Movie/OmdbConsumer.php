<?php

namespace App\Movie;

use App\Movie\Enum\OMDbSearchType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbConsumer
{
    public function __construct(
        private HttpClientInterface $omdbClient
    ) {}

    public function fetchMovie(OMDbSearchType $type, string $value): array
    {
        $data = $this->omdbClient->request(
            'GET',
            '',
            ['query' => [
                $type->value => $value,
            ]]
        )->toArray();

        if (array_key_exists('Error', $data) && $data['Error'] === 'Movie not found!') {
            throw new NotFoundHttpException();
        }

        return $data;
    }
}
