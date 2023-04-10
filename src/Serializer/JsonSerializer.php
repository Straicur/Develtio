<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Jest to serializer wykorzystywany w systemie głównie do serializowania responsów przy pomocy klass modeli
 * oraz deserializowania requestów przy pomocy klass query
 */
class JsonSerializer implements SerializerInterface
{
    private array $encoders;

    private array $normalizers;

    private Serializer $serializer;

    public function __construct()
    {
        $this->encoders = [new JsonEncoder()];
        $this->normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }

    public function serialize(mixed $object): string
    {
        return $this->serializer->serialize($object, "json");
    }

    public function deserialize(mixed $data, string $className): mixed
    {
        return $this->serializer->deserialize($data, $className, "json");
    }
}