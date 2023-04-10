<?php

namespace App\Model;

class UserBookSuccessModel implements ModelInterface
{
    private string $id;
    private string $title;
    private string $description;
    private string $ISBN;
    private int $dateAdded;

    /**
     * @var OpinionModel[]
     */
    private array $opinions = [];

    /**
     * @param string $id
     * @param string $title
     * @param string $description
     * @param string $ISBN
     * @param \DateTime $dateAdded
     */
    public function __construct(string $id, string $title, string $description, string $ISBN, \DateTime $dateAdded)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->ISBN = $ISBN;
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
     * @return OpinionModel[]
     */
    public function getOpinions(): array
    {
        return $this->opinions;
    }

    /**
     * @param OpinionModel[] $opinions
     */
    public function setOpinions(array $opinions): void
    {
        $this->opinions = $opinions;
    }

    public function addOpinion(OpinionModel $opinion)
    {
        $this->opinions[] = $opinion;
    }
}