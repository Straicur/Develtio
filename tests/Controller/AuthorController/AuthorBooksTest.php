<?php

namespace App\Tests\Controller\AuthorController;

use App\Tests\AbstractWebTest;

class AuthorBooksTest extends AbstractWebTest
{
    public function test_authorBooksSuccess()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");
        $user2 = $this->databaseMockManager->testFunc_addUser("test2@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title1", "Desc", "989223933211", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title2", "Desc", "989223933212", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title3", "Desc", "989223933213", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title4", "Desc", "989223933214", $user);

        $book = $this->databaseMockManager->testFunc_addBook("Title5", "Desc", "989223933215", $user2);
        $book = $this->databaseMockManager->testFunc_addBook("Title6", "Desc", "989223933216", $user2);
        $book = $this->databaseMockManager->testFunc_addBook("Title7", "Desc", "989223933217", $user2);
        $book = $this->databaseMockManager->testFunc_addBook("Title8", "Desc", "989223933218", $user2);

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [];

        $crawler = self::$webClient->request("GET", "/api/author/books/0", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);
        /// step 5
        $this->assertIsArray($responseContent);

        $this->assertArrayHasKey("page", $responseContent);
        $this->assertArrayHasKey("books", $responseContent);
        $this->assertCount(4, $responseContent["books"]);
    }

    public function test_authorBooksPageSuccess()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");
        $user2 = $this->databaseMockManager->testFunc_addUser("test2@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title1", "Desc", "989223933211", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title2", "Desc", "989223933212", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title3", "Desc", "989223933213", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title4", "Desc", "989223933214", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title5", "Desc", "989223933215", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title6", "Desc", "989223933216", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title7", "Desc", "989223933217", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title8", "Desc", "989223933218", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title9", "Desc", "989223933219", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title10", "Desc", "989223933222", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title11", "Desc", "989223933223", $user);
        $book = $this->databaseMockManager->testFunc_addBook("Title12", "Desc", "989223933224", $user);

        $book = $this->databaseMockManager->testFunc_addBook("Title5", "Desc", "889223933215", $user2);
        $book = $this->databaseMockManager->testFunc_addBook("Title6", "Desc", "889223933216", $user2);
        $book = $this->databaseMockManager->testFunc_addBook("Title7", "Desc", "889223933217", $user2);
        $book = $this->databaseMockManager->testFunc_addBook("Title8", "Desc", "889223933218", $user2);

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [];

        $crawler = self::$webClient->request("GET", "/api/author/books/1", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);
        /// step 5
        $this->assertIsArray($responseContent);

        $this->assertArrayHasKey("page", $responseContent);
        $this->assertArrayHasKey("books", $responseContent);
        $this->assertCount(2, $responseContent["books"]);
    }

    public function test_authorBooksUnauthorized()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user);

        $crawler = self::$webClient->request("GET", "/api/author/books/0", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ]);

        $this->assertResponseStatusCodeSame(401);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }

    public function test_authorBooksNoPageCredentials()
    {
        $user1 = $this->databaseMockManager->testFunc_addUser("test1@cos.pl", "Dam", "Mos", "Zaq12wsx");
        $user2 = $this->databaseMockManager->testFunc_addUser("test2@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user1);

        $token = $this->databaseMockManager->testFunc_loginUser($user2);

        $crawler = self::$webClient->request("GET", "/api/author/books/", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ]);

        $this->assertResponseStatusCodeSame(404);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);

        $this->assertIsArray($responseContent);
        $this->assertArrayHasKey("error", $responseContent);
        $this->assertArrayHasKey("data", $responseContent);
    }
}