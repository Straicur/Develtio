<?php

namespace App\Tests\Controller\UserController;

use App\Tests\AbstractWebTest;

class UserBooksTest extends AbstractWebTest
{
    public function test_userBooksAuthorizedSuccess()
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

        $crawler = self::$webClient->request("GET", "http://127.0.0.1:8000/api/user/books/0-t-e", server: [
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
        $this->assertArrayHasKey("maxPage", $responseContent);
        $this->assertArrayHasKey("books", $responseContent);
        $this->assertCount(10, $responseContent["books"]);
    }

    public function test_userBooksPageTitleDescriptionSuccess()
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

        $crawler = self::$webClient->request("GET", "http://127.0.0.1:8000/api/user/books/0-t-e");

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);
        /// step 5
        $this->assertIsArray($responseContent);

        $this->assertArrayHasKey("page", $responseContent);
        $this->assertArrayHasKey("maxPage", $responseContent);
        $this->assertArrayHasKey("books", $responseContent);
        $this->assertCount(10, $responseContent["books"]);
    }
    public function test_userBooksPageDescriptionSuccess()
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

        $crawler = self::$webClient->request("GET", "http://127.0.0.1:8000/api/user/books/0-e");

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);
        /// step 5
        $this->assertIsArray($responseContent);

        $this->assertArrayHasKey("page", $responseContent);
        $this->assertArrayHasKey("maxPage", $responseContent);
        $this->assertArrayHasKey("books", $responseContent);
        $this->assertCount(10, $responseContent["books"]);
    }
    public function test_userBooksPageTitleSuccess()
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

        $crawler = self::$webClient->request("GET", "http://127.0.0.1:8000/api/user/books/0-t");

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);
        /// step 5
        $this->assertIsArray($responseContent);

        $this->assertArrayHasKey("page", $responseContent);
        $this->assertArrayHasKey("maxPage", $responseContent);
        $this->assertArrayHasKey("books", $responseContent);
        $this->assertCount(10, $responseContent["books"]);
    }
    public function test_userBooksPageSuccess()
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

        $crawler = self::$webClient->request("GET", "http://127.0.0.1:8000/api/user/books/0");

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);
        /// step 5
        $this->assertIsArray($responseContent);

        $this->assertArrayHasKey("page", $responseContent);
        $this->assertArrayHasKey("maxPage", $responseContent);
        $this->assertArrayHasKey("books", $responseContent);
        $this->assertCount(10, $responseContent["books"]);
    }

    public function test_userBooksNoPageCredentials()
    {
        $user1 = $this->databaseMockManager->testFunc_addUser("test1@cos.pl", "Dam", "Mos", "Zaq12wsx");
        $user2 = $this->databaseMockManager->testFunc_addUser("test2@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $book = $this->databaseMockManager->testFunc_addBook("Title", "Desc", "989223933212", $user1);

        $token = $this->databaseMockManager->testFunc_loginUser($user2);

        $crawler = self::$webClient->request("GET", "http://127.0.0.1:8000/api/user/books/");

        $this->assertResponseStatusCodeSame(404);

        $response = self::$webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);

        $this->assertIsArray($responseContent);
        $this->assertArrayHasKey("error", $responseContent);
        $this->assertArrayHasKey("data", $responseContent);
    }
}