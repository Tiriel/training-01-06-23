<?php

namespace App\Manager;

use App\Repository\BookRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BookManager
{
    public function __construct(
        private BookRepository $repository,
        private MailerInterface $mailer,
        #[Autowire('%env(APP_FOO)%')]
        private string $foo,
        private AuthorizationCheckerInterface $checker
    ) {}

    public function doSomething()
    {
        if ($this->checker->isGranted('ROLE_ADMIN')) {
            //
        }
        $this->repository->count([]);
        $this->mailer->send(new Email());
    }
}
