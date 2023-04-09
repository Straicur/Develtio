<?php

namespace App\Tests\Controller\UserController;

use App\Tests\AbstractWebTest;

class UserBookDetailTest extends AbstractWebTest
{
    public function test_userBookDetailSuccess()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");
        $user2 = $this->databaseMockManager->testFunc_addUser("test2@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title1", "Desc", "989223933211", $user);

        $opinion = $this->databaseMockManager->testFunc_addOpinion(10, "Desc", "Author", "test2@cos.pl", $book);

        $content = [
            "bookId" => $book->getId(),
        ];

        $crawler = self::$webClient->request("POST", "/api/user/book/detail", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);
        /// step 5
        $this->assertIsArray($responseContent);

        $this->assertArrayHasKey("id", $responseContent);
        $this->assertArrayHasKey("title", $responseContent);
        $this->assertArrayHasKey("description", $responseContent);
        $this->assertArrayHasKey("ISBN", $responseContent);
        $this->assertArrayHasKey("dateAdded", $responseContent);
        $this->assertArrayHasKey("opinions", $responseContent);
        $this->assertCount(1, $responseContent["opinions"]);
    }

    public function test_userBookDetailLoggedInSuccess()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");
        $user2 = $this->databaseMockManager->testFunc_addUser("test2@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title1", "Desc", "989223933211", $user);

        $opinion1 = $this->databaseMockManager->testFunc_addOpinion(10, "Desc", "Author", "test2@cos.pl", $book);
        $opinion2 = $this->databaseMockManager->testFunc_addOpinion(10, "Desc", "Author", "test2@cos.pl", $book);
        $opinion3 = $this->databaseMockManager->testFunc_addOpinion(10, "Desc", "Author", "test3@cos.pl", $book);

        $book2 = $this->databaseMockManager->testFunc_addBook("Title1", "Desc", "989223933212", $user);

        $opinion1 = $this->databaseMockManager->testFunc_addOpinion(10, "Desc", "Author", "test2@cos.pl", $book2);
        $opinion2 = $this->databaseMockManager->testFunc_addOpinion(10, "Desc", "Author", "test2@cos.pl", $book2);
        $opinion3 = $this->databaseMockManager->testFunc_addOpinion(10, "Desc", "Author", "test3@cos.pl", $book2);

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [
            "bookId" => $book->getId(),
        ];

        $crawler = self::$webClient->request("POST", "/api/user/book/detail", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);
        /// step 5
        $this->assertIsArray($responseContent);

        $this->assertArrayHasKey("id", $responseContent);
        $this->assertArrayHasKey("title", $responseContent);
        $this->assertArrayHasKey("description", $responseContent);
        $this->assertArrayHasKey("ISBN", $responseContent);
        $this->assertArrayHasKey("dateAdded", $responseContent);
        $this->assertArrayHasKey("opinions", $responseContent);
        $this->assertCount(3, $responseContent["opinions"]);
    }

    public function test_userBookDetailNotExistingIdCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $content = [
            "bookId" => "66666c4e-16e6-1ecc-9890-a7e8b0073d3b",
        ];

        $crawler = self::$webClient->request("POST", "/api/user/book/detail", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseStatusCodeSame(404);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);

        $this->assertIsArray($responseContent);
        $this->assertArrayHasKey("error", $responseContent);
        $this->assertArrayHasKey("data", $responseContent);
    }
    public function test_userBookDetailBadIdCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $content = [
            "bookId" => "666664e-16e6-1ecc-9890-a7e8b073d3b",
        ];

        $crawler = self::$webClient->request("POST", "/api/user/book/detail", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }
    public function test_userBookDetailEmptyCredentials()
    {
        $user1 = $this->databaseMockManager->testFunc_addUser("test1@cos.pl", "Dam", "Mos", "Zaq12wsx");
        $user2 = $this->databaseMockManager->testFunc_addUser("test2@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user1);

        $token = $this->databaseMockManager->testFunc_loginUser($user2);

        $content = [];

        $crawler = self::$webClient->request("POST", "/api/user/book/detail", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }
}