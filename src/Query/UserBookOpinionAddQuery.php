<?php

namespace App\Query;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class UserBookOpinionAddQuery
{
    #[Assert\NotNull(message: "BookId is null")]
    #[Assert\NotBlank(message: "BookId is blank")]
    #[Assert\Uuid]
    private Uuid $bookId;

    #[Assert\NotNull(message: "Email is null")]
    #[Assert\NotBlank(message: "Email is empty")]
    #[Assert\Type(type: "string")]
    #[Assert\Email]
    private string $email;

    #[Assert\NotNull(message: "Description is null")]
    #[Assert\NotBlank(message: "Description is empty")]
    #[Assert\Type(type: "string")]
    #[Assert\Regex(pattern: '/^.{2,500}$/', message: 'Bad description')]
    private string $description;

    #[Assert\NotNull(message: "Author is null")]
    #[Assert\NotBlank(message: "Author is empty")]
    #[Assert\Type(type: "string")]
    #[Assert\Regex(pattern: '/^.{2,100}$/', message: 'Bad author')]
    private string $author;

    #[Assert\NotNull(message: "Rating is null")]
    #[Assert\NotBlank(message: "Rating is empty")]
    #[Assert\Type(type: "integer")]
    #[Assert\Range(
        notInRangeMessage: 'You must be between {{ min }} and {{ max }}',
        min: 0,
        max: 9,
    )]
    private int $rating;

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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param string $rating
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
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