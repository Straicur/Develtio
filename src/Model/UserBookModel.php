<?php

namespace App\Model;

class UserBookModel
{
    private string $id;
    private string $title;
    private string $description;
    private string $ISBN;
    private int $dateAdded;
    private AuthorModel $author;

    /**
     * @param string $id
     * @param string $title
     * @param string $description
     * @param string $ISBN
     * @param \DateTime $dateAdded
     * @param AuthorModel $author
     */
    public function __construct(string $id, string $title, string $description, string $ISBN, \DateTime $dateAdded, AuthorModel $author)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->ISBN = $ISBN;
        $this->dateAdded = $dateAdded->getTimestamp();
        $this->author = $author;
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
        return $this->ISBN;
    }

    /**
     * @param string $ISBN
     */
    public function setISBN(string $ISBN): void
    {
        $this->ISBN = $ISBN;
    }

    /**
     * @return AuthorModel
     */
    public function getAuthor(): AuthorModel
    {
        return $this->author;
    }

    /**
     * @param AuthorModel $author
     */
    public function setAuthor(AuthorModel $author): void
    {
        $this->author = $author;
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

}