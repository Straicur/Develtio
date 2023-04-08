<?php

namespace App\Tests\Controller\AuthorController;

use App\Repository\BookRepository;
use App\Tests\AbstractWebTest;

class AuthorBookEditTest extends AbstractWebTest
{
    public function test_authorBookEditSuccess()
    {
        $bookRepository = $this->getService(BookRepository::class);

        $this->assertInstanceOf(BookRepository::class, $bookRepository);

        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [
            "bookId" => $book->getId(),
            "title" => "Title2",
            "description" => "Desc2"
        ];

        $crawler = self::$webClient->request("PATCH", "/api/author/book/edit", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $bookAfter = $bookRepository->findOneBy([
            "id" => $content["bookId"]
        ]);

        $this->assertNotNull($bookAfter);

        $this->assertSame($bookAfter->getTitle(), $content["title"]);
        $this->assertSame($bookAfter->getDescription(), $content["description"]);
    }

    public function test_authorBookEditUnauthorized()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $content = [
            "bookId" => $book->getId(),
            "title" => "Title",
            "description" => "Desc"
        ];

        $crawler = self::$webClient->request("PATCH", "/api/author/book/edit", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseStatusCodeSame(401);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }

    public function test_authorBookEditNotOwnerCredentials()
    {
        $user1 = $this->databaseMockManager->testFunc_addUser("test1@cos.pl", "Dam", "Mos", "Zaq12wsx");
        $user2 = $this->databaseMockManager->testFunc_addUser("test2@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user1);

        $token = $this->databaseMockManager->testFunc_loginUser($user2);

        $content = [
            "bookId" => $book->getId(),
            "title" => "Title",
            "description" => "Desc"
        ];

        $crawler = self::$webClient->request("PATCH", "/api/author/book/edit", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseStatusCodeSame(404);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);

        $this->assertIsArray($responseContent);
        $this->assertArrayHasKey("error", $responseContent);
        $this->assertArrayHasKey("data", $responseContent);
    }

    public function test_authorBookEditBadIdCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [
            "bookId" => "66666c4e-16e6-1ecc-9890-a7e8b0073d3b",
            "title" => "Title",
            "description" => "Desc"
        ];

        $crawler = self::$webClient->request("PATCH", "/api/author/book/edit", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseStatusCodeSame(404);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);

        $this->assertIsArray($responseContent);
        $this->assertArrayHasKey("error", $responseContent);
        $this->assertArrayHasKey("data", $responseContent);
    }

    public function test_authorBookEditEmptyCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [];

        $crawler = self::$webClient->request("PATCH", "/api/author/book/edit", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }
}