<?php

namespace App\Tests\Controller\AuthController;

use App\Repository\UserRepository;
use App\Tests\AbstractWebTest;

class RegistrationTest extends AbstractWebTest
{
    public function test_registrationSuccess()
    {
        $userRepository = $this->getService(UserRepository::class);

        $this->assertInstanceOf(UserRepository::class, $userRepository);

        $content = [
            "email" => "mosinskidamian21@gmail.com",
            "firstname" => "Damian",
            "lastname" => "Mosiński",
            "password" => "Zaq12wsx",
            "confirmPassword" => "Zaq12wsx",
        ];

        $crawler = self::$webClient->request("PUT", "/api/register", content: json_encode($content));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        $userAfter = $userRepository->findOneBy([
            "email" => $content["email"]
        ]);

        $this->assertNotNull($userAfter);
    }

    public function test_registrationIncorrectUsedEmailExistsCredentials(): void
    {
        $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $content = [
            "email" => "test@cos.pl",
            "firstname" => "Damian",
            "lastname" => "Mosiński",
            "password" => "Zaq12wsx",
            "confirmPassword" => "Zaq12wsx",
        ];

        $crawler = self::$webClient->request("PUT", "/api/register", content: json_encode($content));

        $this->assertResponseStatusCodeSame(404);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);

        $this->assertIsArray($responseContent);
        $this->assertArrayHasKey("error", $responseContent);
        $this->assertArrayHasKey("data", $responseContent);
    }

    public function test_registrationIncorrectPasswordCredentials(): void
    {
        $content = [
            "email" => "test2@cos.pl",
            "firstname" => "Damian",
            "lastname" => "Mosiński",
            "password" => "Zaq12wsx",
            "confirmPassword" => "Zaq13wsx",
        ];

        $crawler = self::$webClient->request("PUT", "/api/register", content: json_encode($content));

        $this->assertResponseStatusCodeSame(404);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);

        $this->assertIsArray($responseContent);
        $this->assertArrayHasKey("error", $responseContent);
        $this->assertArrayHasKey("data", $responseContent);
    }

    public function test_registrationOneEmptyRequest()
    {
        $content = [
            "email" => "test2@cos.pl",
            "firstname" => "Damian",
            "password" => "Zaq12wsx",
            "confirmPassword" => "Zaq13wsx",
        ];

        $crawler = self::$webClient->request("PUT", "/api/register", content: json_encode($content));

        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }

    public function test_registrationBadEmailRequest()
    {
        $content = [
            "email" => "test2@.p",
            "firstname" => "Damian",
            "lastname" => "Mosiński",
            "password" => "Zaq12wsx",
            "confirmPassword" => "Zaq13wsx",
        ];

        $crawler = self::$webClient->request("PUT", "/api/register", content: json_encode($content));

        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }

    public function test_registrationBadFirstnameRequest()
    {
        $content = [
            "email" => "test2@cos.pl",
            "firstname" => "1111",
            "lastname" => "Mosiński",
            "password" => "Zaq12wsx",
            "confirmPassword" => "Zaq13wsx",
        ];

        $crawler = self::$webClient->request("PUT", "/api/register", content: json_encode($content));

        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }

    public function test_registrationBadLastnameRequest()
    {
        $content = [
            "email" => "test2@cos.pl",
            "firstname" => "Damian",
            "lastname" => "2222",
            "password" => "Zaq12wsx",
            "confirmPassword" => "Zaq13wsx",
        ];

        $crawler = self::$webClient->request("PUT", "/api/register", content: json_encode($content));

        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }

    public function test_registrationBadPasswordRequest()
    {
        $content = [
            "email" => "test2@cos.pl",
            "firstname" => "Damian",
            "lastname" => "Mosiński",
            "password" => "zaq12wsx",
            "confirmPassword" => "Zaq13wsx",
        ];

        $crawler = self::$webClient->request("PUT", "/api/register", content: json_encode($content));

        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }

    public function test_registrationBadConfirmPasswordRequest()
    {
        $content = [
            "email" => "test2@cos.pl",
            "firstname" => "Damian",
            "lastname" => "Mosiński",
            "password" => "Zaq12wsx",
            "confirmPassword" => "zaq13wsx",
        ];

        $crawler = self::$webClient->request("PUT", "/api/register", content: json_encode($content));

        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }

    public function test_registrationEmptyRequest()
    {
        $content = [];

        $crawler = self::$webClient->request("PUT", "/api/register", content: json_encode($content));

        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }
}