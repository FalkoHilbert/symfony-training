<?php

namespace App\Entity;

interface CreatedByInterface
{
    public function getCreatedBy(): ?User;
}