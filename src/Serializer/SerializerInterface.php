<?php

namespace App\Serializer;

interface SerializerInterface
{
    public function serialize(mixed $object): string;

    public function deserialize(mixed $data, string $className): mixed;
}