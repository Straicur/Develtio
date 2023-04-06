<?php

namespace App\Model;

class PermissionNotGrantedModel implements ModelInterface
{
    private string $error = "Permission not granted";

    private string $description = "Authorized user don't have permission to do this";

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}