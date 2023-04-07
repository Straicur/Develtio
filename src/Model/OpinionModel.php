<?php

namespace App\Model;

class OpinionModel
{
    private string $id;
    private string $author;
    private string $description;
    private string $email;
    private int $rating;
    private int $dateAdded;


    /**
     * @param string $id
     * @param string $author
     * @param string $description
     * @param string $email
     * @param int $rating
     * @param \DateTime $dateAdded
     */
    public function __construct( string $id, string $author, string $description, string $email, int $rating, \DateTime $dateAdded)
    {
        $this->id = $id;
        $this->author = $author;
        $this->description = $description;
        $this->email = $email;
        $this->rating = $rating;
        $this->dateAdded = $dateAdded->getTimestamp();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
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
     * @return int
     */
    public function getDateAdded(): int
    {
        return $this->dateAdded;
    }

    /**
     * @param \DateTime $dateAdded
     */
    public function setDateAdded(\DateTime $dateAdded): void
    {
        $this->dateAdded = $dateAdded->getTimestamp();
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
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }
}