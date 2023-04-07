<?php

namespace App\Model;

class AuthorBookModel
{
    private string $title;
    private string $description;
    private string $ISBN;

    /**
     * @param string $title
     * @param string $description
     * @param string $ISBN
     */
    public function __construct(string $title, string $description, string $ISBN)
    {
        $this->title = $title;
        $this->description = $description;
        $this->ISBN = $ISBN;
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

}