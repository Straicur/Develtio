<?php

namespace App\Query;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class AuthorBookDeleteQuery
{
    #[Assert\NotNull(message: "BookId is null")]
    #[Assert\NotBlank(message: "BookId is blank")]
    #[Assert\Uuid]
    private Uuid $bookId;

    /**
     * @return Uuid
     */
    #[OA\Property(type: "string", example: "60266c4e-16e6-1ecc-9890-a7e8b0073d3b")]
    public function getBookId(): Uuid
    {
        return $this->bookId;
    }

    /**
     * @param string $bookId
     */
    public function setBookId(string $bookId): void
    {
        $this->bookId = Uuid::fromString($bookId);
    }

}