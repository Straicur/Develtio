<?php

namespace App\Exception;

use App\Model\JsonDataInvalidModel;
use App\Tool\ResponseTool;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidJsonDataException extends \Exception implements ResponseExceptionInterface
{
    private string $className;

    private ?ConstraintViolationListInterface $validationErrors;

    private ?array $errors;

    /**
     * @param string $className
     * @param ConstraintViolationListInterface|null $validationErrors
     * @param string[]|null $errors
     */
    public function __construct(string $className, ?ConstraintViolationListInterface $validationErrors = null, ?array $errors = null)
    {
        parent::__construct("Bad request");

        $this->className = $className;
        $this->validationErrors = $validationErrors;
        $this->errors = $errors;
    }

    public function getResponse(): Response
    {
        $validationErrors = [];

        for ($i = 0; $i < $this->validationErrors?->count(); $i++) {
            $validationError = $this->validationErrors->get($i);

            $validationErrors[] = "[" . $validationError->getPropertyPath() . "] -> " . $validationError->getMessage();
        }

        if ($this->errors != null) {
            foreach ($this->errors as $error) {
                $validationErrors[] = $error;
            }
        }

        return ResponseTool::getResponse(new JsonDataInvalidModel($this->className, $validationErrors), 400);
    }
}