<?php

namespace App\Model;

class DataNotFoundModel implements ModelInterface
{
    private string $error = "Data not found";

    private array $data;

    /**
     * @param string[] $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * @return string[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string[] $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }
}