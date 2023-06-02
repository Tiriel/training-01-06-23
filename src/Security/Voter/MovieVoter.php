<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MovieVoter extends Voter
{
    public const EDIT = 'movie.edit';
    public const VIEW = 'movie.view';

    public function __construct(
        private AuthorizationCheckerInterface $checker
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ($this->checker->isGranted('ROLE_ADMIN')) {
            return true;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->checkView($subject, $user),
            self::EDIT => $this->checkEdit($subject, $user),
            default => false
        };
    }

    public function checkView(Movie $movie, User $user): bool
    {
        $age = $user->getBirthday()?->diff(new \DateTimeImmutable())->y ?? null;

        return match ($movie->getRated()) {
            'G' => true,
            'PG', 'PG-13' => $age && $age >= 13,
            'R', 'NC-17' => $age && $age >= 17,
            default => false
        };
    }

    public function checkEdit(Movie $movie, User $user): bool
    {
        return $this->checkView($movie, $user) && $movie->getCreatedBy() === $user;
    }
}
