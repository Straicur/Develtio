<?php

namespace App\Entity;

use App\Repository\OpinionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OpinionRepository::class)]
class Opinion
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    #[Assert\Range(
        notInRangeMessage: 'You must be between {{ min }} and {{ max }}',
        min: 0,
        max: 9,
    )]
    #[ORM\Column(type: 'integer')]
    private int $rating;

    #[Assert\Regex(pattern: '/^.{2,500}$/', message: 'Bad description')]
    #[ORM\Column(type: 'string', length: 500)]
    private string $description;

    #[Assert\Regex(pattern: '/^.{2,100}$/', message: 'Bad author')]
    #[ORM\Column(type: 'string', length: 100)]
    private string $author;

    #[Assert\Email]
    #[ORM\Column(type: 'string', length: 180)]
    private string $email;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $dateAdded;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'opinions')]
    #[ORM\JoinColumn(nullable: false)]
    private Book $book;

    /**
     * @param int $rating
     * @param string $description
     * @param string $author
     * @param string $email
     * @param Book $book
     */
    public function __construct(int $rating, string $description, string $author, string $email, Book $book)
    {
        $this->rating = $rating;
        $this->description = $description;
        $this->author = $author;
        $this->email = $email;
        $this->dateAdded = new \DateTime("Now");
        $this->book = $book;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateAdded(): \DateTime
    {
        return $this->dateAdded;
    }

    public function setDateAdded(\DateTime $dateAdded): self
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function setBook(Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}
