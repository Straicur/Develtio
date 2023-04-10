<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\AuthenticationException;

/**
 * Jest to serwis wykorzystywany do przechowywania zalogowanego użytkownika już po znalezieniu go w AuthValidationSubscriber
 */
class AuthorizedUserService implements AuthorizedUserServiceInterface
{
    private static ?User $authorizedUser = null;

    public static function setAuthorizedUser(User $user)
    {
        self::$authorizedUser = $user;
    }

    /**
     * @throws AuthenticationException
     */
    public static function getAuthorizedUser(): User
    {
        if (self::$authorizedUser == null) {
            throw new AuthenticationException();
        }

        return self::$authorizedUser;
    }
}