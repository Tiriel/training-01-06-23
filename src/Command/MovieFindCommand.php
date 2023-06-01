<?php

namespace App\Command;

use App\Entity\Movie;
use App\Movie\MovieProvider;
use App\Movie\OMDbSearchType;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsCommand(
    name: 'app:movie:find',
    description: 'Add a short description for your command',
)]
class MovieFindCommand extends Command
{
    private ?string $value = null;
    private ?OMDbSearchType $mode = null;
    private ?SymfonyStyle $io = null;

    public function __construct(
        protected readonly MovieProvider $provider,
        protected readonly MovieRepository $repository
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('value', InputArgument::OPTIONAL, 'The title or id of the movie you are searching for.')
            ->addArgument('mode', InputArgument::OPTIONAL, 'The the type of the provided value (id or title).')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);

        if (!$this->value = $input->getArgument('value')) {
            $this->value = $this->io->ask("What is the title or the id of the movie you are searching for?");
        }

        $modeValue = $input->getArgument('mode');
        if ($modeValue && ($modeValue === 'title' || $modeValue === 'id')) {
            $modeValue = $modeValue[0];
        }

        $mode = OMDbSearchType::tryFrom($modeValue);
        while (!\in_array($mode, OMDbSearchType::cases())) {
            $mode = OMDbSearchType::tryFrom($this->io->choice("What is the type of the value you are searching?", ['i' => 'id', 't' => 'title']));
        }
        $this->mode = $mode;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->provider->setIo($this->io);

        $fullMode = $this->mode->value === 'i' ? 'id' : 'title';
        $this->io->title(sprintf("You are searching for a movie with a %s \"%s\"", $fullMode, $this->value));

        if ($this->mode === OMDbSearchType::ID && $movie = $this->repository->findOneBy(['imdbId' => $this->value])) {
            $this->io->info('Movie already in database!');
            $this->displayTable($movie);

            return Command::SUCCESS;
        }

        try {
            $movie = $this->provider->getMovie($this->mode, $this->value);
        } catch (NotFoundHttpException) {
            $this->io->error('Movie not found!');

            return Command::FAILURE;
        }
        $this->displayTable($movie);

        return Command::SUCCESS;
    }

    private function displayTable(Movie $movie): void
    {
        $this->io->table(
            ['id', 'imdbId', 'Title', 'Rated'],
            [[$movie->getId(), $movie->getImdbId(), $movie->getTitle(), $movie->getRated()]]
        );
        $this->io->success('Done!');
    }
}
