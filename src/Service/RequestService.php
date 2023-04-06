<?php

namespace App\Service;

use App\Exception\InvalidJsonDataException;
use App\Serializer\JsonSerializer;
use App\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestService implements RequestServiceInterface
{
    private ValidatorInterface $validator;

    private SerializerInterface $serializer;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->serializer = new JsonSerializer();
    }

    /**
     * @throws InvalidJsonDataException
     */
    public function getRequestBodyContent(Request $request, string $className): object
    {
        $bodyContent = $request->getContent();

        try {
            $query = $this->serializer->deserialize($bodyContent, $className);
        } catch (\Exception $e) {
            throw new InvalidJsonDataException($className, null, [$e->getMessage()]);
        }

        if ($query instanceof $className) {
            $validationErrors = $this->validator->validate($query);
            if ($validationErrors->count() > 0) {
                throw new InvalidJsonDataException($className, $validationErrors);
            }

            return $query;
        } else {
            throw new InvalidJsonDataException($className);
        }
    }
}