<?php

namespace App\Tests\Controller\UserController;

use App\Repository\OpinionRepository;
use App\Tests\AbstractWebTest;

class UserBookOpinionAddTest extends AbstractWebTest
{
    public function test_userBookOpinionAddSuccess()
    {
        $opinionRepository = $this->getService(OpinionRepository::class);

        $this->assertInstanceOf(OpinionRepository::class, $opinionRepository);

        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $content = [
            "bookId" => $book->getId(),
            "email" => "test321@cos.pl",
            "description" => "Description",
            "author" => "Author",
            "rating" => 9,
        ];

        $crawler = self::$webClient->request("PUT", "/api/user/book/opinion/add", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        $opinionAfter = $opinionRepository->findOneBy([
            "email" => $content["email"]
        ]);

        $this->assertNotNull($opinionAfter);
    }

    public function test_userBookOpinionAddLoggedIdSuccess()
    {
        $opinionRepository = $this->getService(OpinionRepository::class);

        $this->assertInstanceOf(OpinionRepository::class, $opinionRepository);

        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [
            "bookId" => $book->getId(),
            "email" => "test321@cos.pl",
            "description" => "Description",
            "author" => "Author",
            "rating" => 9,
        ];

        $crawler = self::$webClient->request("PUT", "/api/user/book/opinion/add", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        $opinionAfter = $opinionRepository->findOneBy([
            "email" => $content["email"]
        ]);

        $this->assertNotNull($opinionAfter);
    }

    public function test_userBookOpinionAddOneEmptyCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $content = [
            "bookId" => $book->getId(),
            "email" => "test321@cos.pl",
            "description" => "Description",
            "rating" => 9,
        ];

        $crawler = self::$webClient->request("PUT", "/api/user/book/opinion/add", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));


        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }

    public function test_userBookOpinionAddCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $content = [
            "bookId" => "66666c4e-16e6-1ecc-9890-a7e8b0073d3b",
            "email" => "test321@cos.pl",
            "description" => "Description",
            "author" => "Author",
            "rating" => 9,
        ];

        $crawler = self::$webClient->request("PUT", "/api/user/book/opinion/add", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));
        $this->assertResponseStatusCodeSame(404);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);

        $this->assertIsArray($responseContent);
        $this->assertArrayHasKey("error", $responseContent);
        $this->assertArrayHasKey("data", $responseContent);
    }

    public function test_userBookOpinionAddEmptyCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $content = [];

        $crawler = self::$webClient->request("PUT", "/api/user/book/opinion/add", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));


        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }
}