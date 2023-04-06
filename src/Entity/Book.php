<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    #[Assert\Regex(pattern: '/^.{1,200}$/', message: 'Bad title')]
    #[ORM\Column(type: 'string', length: 200)]
    private string $title;

    #[Assert\Regex(pattern: '/^.{1,}$/', message: 'Bad description')]
    #[ORM\Column(type: 'text')]
    private string $description;

    #[Assert\Regex(pattern: '/^[0-9]{4,15}$/', message: 'Bad ISBN')]
    #[ORM\Column(type: 'string', length: 13, unique: true)]
    private string $ISBN;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $dateAdded;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Opinion::class)]
    private Collection $opinions;

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
        $this->dateAdded = new \DateTime("Now");
        $this->opinions = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getISBN(): string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): self
    {
        $this->ISBN = $ISBN;

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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Opinion>
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinion $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions[] = $opinion;
            $opinion->setBook($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getBook() === $this) {
                $opinion->setBook(null);
            }
        }

        return $this;
    }
}
