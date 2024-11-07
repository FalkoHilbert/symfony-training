<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class EditVoter implements VoterInterface
{
    public const string EDIT = 'ENTITY_EDIT';

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $user = $token->getUser();
        if (! $user instanceof UserInterface) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
        if ( !in_array(self::EDIT, $attributes, true)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
        return $this->voteForEdit($user, $subject);
    }

    abstract protected function voteForEdit(UserInterface $user, mixed $subject): int;
}