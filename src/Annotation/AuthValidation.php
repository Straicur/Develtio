<?php

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * Annotation class for @AuthValidation()
 *
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"METHOD"})
 *
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class AuthValidation
{
    private bool $checkAuthToken;

    /**
     * @param bool $checkAuthToken
     */
    public function __construct(bool $checkAuthToken)
    {
        $this->checkAuthToken = $checkAuthToken;
    }

    /**
     * @return bool
     */
    public function isCheckAuthToken(): bool
    {
        return $this->checkAuthToken;
    }

    /**
     * @param bool $checkAuthToken
     */
    public function setCheckAuthToken(bool $checkAuthToken): void
    {
        $this->checkAuthToken = $checkAuthToken;
    }
}