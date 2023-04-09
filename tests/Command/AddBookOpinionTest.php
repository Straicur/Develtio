<?php

namespace App\Tests\Command;

use App\Repository\OpinionRepository;
use App\Tests\AbstractKernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AddBookOpinionTest extends AbstractKernelTestCase
{
    public function test_addBookOpinionTSuccess()
    {
        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");
        $book = $this->databaseMockManager->testFunc_addBook("Fajny", "Desc", "1234567890123", $user);

        $opinionRepository = $this->getService(OpinionRepository::class);

        $this->assertInstanceOf(OpinionRepository::class, $opinionRepository);

        $cmd = $this->commandApplication->find("app:add:book:opinion");

        $tester = new CommandTester($cmd);

        $tester->execute(["bookTitle" => $book->getTitle(), "rating" => 10, "description" => "Aescription", "author" => "Dam", "email" => "mosinskidamian21@gmail.com"]);

        $tester->assertCommandIsSuccessful();


        $suerAfter = $opinionRepository->findOneBy([
            "email" => "mosinskidamian21@gmail.com"
        ]);

        $this->assertNotNull($suerAfter);
    }
}