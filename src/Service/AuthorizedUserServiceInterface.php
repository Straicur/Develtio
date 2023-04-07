<?php

namespace App\Service;

use App\Entity\User;

interface AuthorizedUserServiceInterface
{
    public static function getAuthorizedUser(): User;

}