<?php

namespace App\Model;

class JsonDataInvalidModel implements ModelInterface
{
    private string $error = "Invalid JSON Data";

    private string $expectingClass;

    private array $validationErrors;

    /**
     * @param string $expectingClass
     * @param string[] $validationErrors
     */
    public function __construct(string $expectingClass, array $validationErrors = [])
    {
        $this->expectingClass = $expectingClass;
        $this->validationErrors = $validationErrors;
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
     * @return string
     */
    public function getExpectingClass(): string
    {
        return $this->expectingClass;
    }

    /**
     * @param string $expectingClass
     */
    public function setExpectingClass(string $expectingClass): void
    {
        $this->expectingClass = $expectingClass;
    }

    /**
     * @return string[]
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    /**
     * @param string[] $validationErrors
     */
    public function setValidationErrors(array $validationErrors): void
    {
        $this->validationErrors = $validationErrors;
    }
}