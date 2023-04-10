<?php

namespace App\Tool;

use App\Model\ModelInterface;
use App\Serializer\JsonSerializer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Jest to prosty tool który służy do serializacji podanej klasy z wszystkimi zmiennymi w niej do do odpowiedniej postaci Jsona.
 * Na koniec tworzy response z zserializowanego obiektu oraz podanego wcześniej kodu http
 */
class ResponseTool
{
    private static array $headers = [
        "Content-Type" => "application/json"
    ];

    public static function getResponse(?ModelInterface $responseModel = null, int $httpCode = 200): Response
    {
        $serializeService = new JsonSerializer();

        $serializedObject = $responseModel != null ? $serializeService->serialize($responseModel) : null;

        return new Response($serializedObject, $httpCode, self::$headers);
    }
}