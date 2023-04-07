<?php

namespace App\Query;

use Symfony\Component\Validator\Constraints as Assert;

class AuthorBookAddQuery
{
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

    #[Assert\NotNull(message: "ISBN is null")]
    #[Assert\NotBlank(message: "ISBN is empty")]
    #[Assert\Type(type: "string")]
    #[Assert\Regex(pattern: '/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/', message: 'Bad ISBN')]
    private string $ISBN;

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
     * @return string
     */
    public function getISBN(): string
    {
        $ISBNDecoded = explode("-", $this->ISBN);

        return implode($ISBNDecoded);
    }

    /**
     * @param string $ISBN
     */
    public function setISBN(string $ISBN): void
    {
        $this->ISBN = $ISBN;
    }

}