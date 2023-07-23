<?php
declare(strict_types=1);

namespace App\Entity;

interface HasOwnerInterface
{
    public function getOwner(): User;
}