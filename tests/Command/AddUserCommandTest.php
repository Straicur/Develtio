<?php

namespace App\Tests\Command;

use App\Repository\UserRepository;
use App\Tests\AbstractKernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AddUserCommandTest extends AbstractKernelTestCase
{
    public function test_addUserCommandSuccess()
    {

        $userRepository = $this->getService(UserRepository::class);

        $this->assertInstanceOf(UserRepository::class, $userRepository);

        $cmd = $this->commandApplication->find("app:add:user");

        $tester = new CommandTester($cmd);

        $tester->execute(["firstname" => "Dam", "lastname" => "Mos", "email" => "mosinskidamian21@gmail.com", "password" => "Zaq12wsx"]);

        $tester->assertCommandIsSuccessful();

        $suerAfter = $userRepository->findOneBy([
            "email" => "mosinskidamian21@gmail.com"
        ]);

        $this->assertNotNull($suerAfter);
    }
}