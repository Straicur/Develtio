<?php

namespace App\Tests\Controller\AuthorController;

use App\Repository\BookRepository;
use App\Tests\AbstractWebTest;

class AuthorBookDeleteTest extends AbstractWebTest
{
    public function test_authorBookDeleteSuccess()
    {
        $bookRepository = $this->getService(BookRepository::class);

        $this->assertInstanceOf(BookRepository::class, $bookRepository);

        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [
            "bookId" => $book->getId(),
        ];

        $crawler = self::$webClient->request("DELETE", "/api/author/book/delete", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $bookAfter = $bookRepository->findOneBy([
            "id" => $content["bookId"]
        ]);

        $this->assertNull($bookAfter);
    }

    public function test_authorBookDeleteUnauthorized()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $content = [
            "bookId" => $book->getId(),
        ];

        $crawler = self::$webClient->request("DELETE", "/api/author/book/delete", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseStatusCodeSame(401);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }

    public function test_authorBookDeleteNotOwnerCredentials()
    {
        $user1 = $this->databaseMockManager->testFunc_addUser("test1@cos.pl", "Dam", "Mos", "Zaq12wsx");
        $user2 = $this->databaseMockManager->testFunc_addUser("test2@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user1);

        $token = $this->databaseMockManager->testFunc_loginUser($user2);

        $content = [
            "bookId" => $book->getId(),
        ];

        $crawler = self::$webClient->request("DELETE", "/api/author/book/delete", server: [
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

    public function test_authorBookDeleteBookHasOpinionsCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $opinion = $this->databaseMockManager->testFunc_addOpinion(10, "Desc", "Author", "test2@cos.pl", $book);

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [
            "bookId" => $book->getId(),
        ];

        $crawler = self::$webClient->request("DELETE", "/api/author/book/delete", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $crawler = self::$webClient->request("DELETE", "/api/author/book/delete", server: [
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

    public function test_authorBookDeleteBadIdCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [
            "bookId" => "66666c4e-16e6-1ecc-9890-a7e8b0073d3b",
        ];

        $crawler = self::$webClient->request("DELETE", "/api/author/book/delete", server: [
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

    public function test_authorBookDeleteEmptyCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [];

        $crawler = self::$webClient->request("DELETE", "/api/author/book/delete", server: [
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