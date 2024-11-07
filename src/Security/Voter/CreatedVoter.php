<?php

namespace App\Security\Voter;

use App\Entity\CreatedByInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class CreatedVoter extends EditVoter
{
    public function voteForEdit(UserInterface $user, mixed $subject): int
    {
        if ( ! $subject instanceof CreatedByInterface)
        {
            return VoterInterface::ACCESS_ABSTAIN;
        }
        return $subject->getCreatedBy() === $user ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
    }
}
