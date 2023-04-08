<?php

namespace App\Tests\Controller\AuthorController;

use App\Repository\BookRepository;
use App\Tests\AbstractWebTest;

class AuthorBookAddTest extends AbstractWebTest
{
    public function test_authorBookAddSuccess()
    {
        $bookRepository = $this->getService(BookRepository::class);

        $this->assertInstanceOf(BookRepository::class, $bookRepository);

        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl","Dam","Mos","Zaq12wsx");

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [
            "title" => "FajnyTitle",
            "description" => "Desc",
            "ISBN" => "989-2239-332-121",
        ];

        $crawler = self::$webClient->request("PUT", "/api/author/book/add", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        $bookAfter = $bookRepository->findOneBy([
            "title"=>$content["title"]
        ]);

        $this->assertNotNull($bookAfter);
    }
    public function test_authorBookAddUnauthorized()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl","Dam","Mos","Zaq12wsx");

        $content = [
            "title" => "Title",
            "description" => "Desc",
            "ISBN" => "989-2239-332-121",
        ];

        $crawler = self::$webClient->request("PUT", "/api/author/book/add", server: [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));


        $this->assertResponseStatusCodeSame(401);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }
    public function test_authorBookAddIncorrectCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl","Dam","Mos","Zaq12wsx");

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [
            "title" => "Title",
            "description" => "Desc",
            "ISBN" => "989-2239-12123321321",
        ];

        $crawler = self::$webClient->request("PUT", "/api/author/book/add", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));


        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }
    public function test_authorBookAddOneEmptyCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl","Dam","Mos","Zaq12wsx");

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [
            "title" => "Title",
            "ISBN" => "989-2239-12123321321",
        ];

        $crawler = self::$webClient->request("PUT", "/api/author/book/add", server: [
            'HTTP_Authorization' => sprintf('%s %s', 'Bearer', $token),
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], content: json_encode($content));


        $this->assertResponseStatusCodeSame(400);

        $responseContent = self::$webClient->getResponse()->getContent();

        $this->assertNotNull($responseContent);
        $this->assertNotEmpty($responseContent);
        $this->assertJson($responseContent);
    }
    public function test_authorBookAddEmptyCredentials()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl","Dam","Mos","Zaq12wsx");

        $token = $this->databaseMockManager->testFunc_loginUser($user);

        $content = [];

        $crawler = self::$webClient->request("PUT", "/api/author/book/add", server: [
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