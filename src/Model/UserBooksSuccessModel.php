<?php

namespace App\Model;

class UserBooksSuccessModel implements ModelInterface
{
    /**
     * @var UserBookModel[]
     */
    private array $books = [];

    private string $page;


    private int $maxPage;

    /**
     * @return int
     */
    public function getPage(): string
    {
        return $this->page;
    }

    /**
     * @param string $page
     */
    public function setPage(string $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getMaxPage(): int
    {
        return $this->maxPage;
    }

    /**
     * @param int $maxPage
     */
    public function setMaxPage(int $maxPage): void
    {
        $this->maxPage = $maxPage;
    }

    /**
     * @return UserBookModel[]
     */
    public function getBooks(): array
    {
        return $this->books;
    }

    /**
     * @param UserBookModel[] $books
     */
    public function setBooks(array $books): void
    {
        $this->books = $books;
    }

    public function addBook(UserBookModel $book)
    {
        $this->books[] = $book;
    }
}