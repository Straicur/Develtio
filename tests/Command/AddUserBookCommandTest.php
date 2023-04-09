<?php

namespace App\Tests\Command;

use App\Repository\BookRepository;
use App\Tests\AbstractKernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AddUserBookCommandTest extends AbstractKernelTestCase
{
    public function test_addUserBookSuccess()
    {

        $user = $this->databaseMockManager->testFunc_addUser("test@cos.pl", "Dam", "Mos", "Zaq12wsx");

        $bookRepository = $this->getService(BookRepository::class);

        $this->assertInstanceOf(BookRepository::class, $bookRepository);

        $cmd = $this->commandApplication->find("app:add:user:book");

        $tester = new CommandTester($cmd);

        $tester->execute(["userEmail" => $user->getEmail(), "title" => "Fajny", "description" => "Description", "ISBN" => "1234567890123"]);

        $tester->assertCommandIsSuccessful();

        $suerAfter = $bookRepository->findOneBy([
            "title" => "Fajny"
        ]);

        $this->assertNotNull($suerAfter);
    }
}