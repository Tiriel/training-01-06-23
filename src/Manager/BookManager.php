<?php

namespace App\Manager;

use App\Repository\BookRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class BookManager
{
    public function __construct(
        private BookRepository $repository,
        private MailerInterface $mailer,
        #[Autowire('%env(APP_FOO)%')]
        private string $foo
    ) {}

    public function doSomething()
    {
        $this->repository->count([]);
        $this->mailer->send(new Email());
    }
}
