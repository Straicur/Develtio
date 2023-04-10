<?php

namespace App\Query;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class AuthorBookEditQuery
{
    #[Assert\NotNull(message: "BookId is null")]
    #[Assert\NotBlank(message: "BookId is blank")]
    #[Assert\Uuid]
    private Uuid $bookId;

    #[Assert\NotNull(message: "Title is null")]
    #[Assert\NotBlank(message: "Title is empty")]
    #[Assert\Type(type: "string")]
    #[Assert\Length(min: 2, max: 50)]
    #[Assert\Regex(pattern: '/^.{1,200}$/', message: 'Bad title')]
    private string $title;

    #[Assert\NotNull(message: "Description is null")]
    #[Assert\NotBlank(message: "Description is empty")]
    #[Assert\Type(type: "string")]
    #[Assert\Regex(pattern: '/^.{1,}$/', message: 'Bad description')]
    private string $description;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

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