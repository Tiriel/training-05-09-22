<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MovieRatedVoter extends Voter
{
    public const EDIT = 'RATED_EDIT';
    public const VIEW = 'RATED_VIEW';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        assert($subject instanceof Movie);
        if ('G' === $subject->getRated()) {
            return true;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->checkView($subject, $user),
            self::EDIT => $this->checkEdit($subject, $user),
            default => false,
        };
    }

    public function checkView(Movie $movie, User $user): bool
    {
        if (null === $user->getBirthday()) {
            return false;
        }

        $age = (new \DateTimeImmutable())->diff($user->getBirthday())->y;
        return match ($movie->getRated()) {
            'PG', 'PG-13' => $age >= 13,
            'R', 'NC-17' => $age >= 17,
            default => false,
        };
    }

    public function checkEdit(Movie $movie, User $user): bool
    {
        return $this->checkView($movie, $user) && $user === $movie->getAddedBy();
    }
}
