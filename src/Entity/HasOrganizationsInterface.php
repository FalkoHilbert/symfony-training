<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface HasOrganizationsInterface
{
    /**
     * @return Collection<int, Organization>
     */
    public function getOrganizations(): Collection;

}