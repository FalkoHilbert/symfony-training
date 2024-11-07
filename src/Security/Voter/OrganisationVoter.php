<?php

namespace App\Security\Voter;

use App\Entity\HasOrganizationsInterface;
use App\Entity\Organization;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class OrganisationVoter extends EditVoter
{
    protected function voteForEdit(UserInterface $user, mixed $subject): int
    {
        if (!$subject instanceof HasOrganizationsInterface ) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if ( !$user instanceof HasOrganizationsInterface) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        return $user->getOrganizations()->reduce(
            function(bool $carry, Organization $organization) use ($subject) {
                return $carry || $subject->getOrganizations()->contains($organization);
            }
            , false) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
    }
}
