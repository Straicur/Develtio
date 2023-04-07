<?php

namespace App\Tests;

use App\Entity\Book;
use App\Entity\Opinion;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\OpinionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DatabaseMockManager
{
    private KernelInterface $kernel;
    private KernelBrowser $webClient;

    public function __construct(KernelInterface $kernel, KernelBrowser $webClient)
    {
        $this->kernel = $kernel;
        $this->webClient = $webClient;
    }

    protected function getService(string $serviceName): object
    {
        return $this->kernel->getContainer()->get($serviceName);
    }

    public function testFunc_loginUser(User $user)
    {
        $content = [
            "email" => $user->getEmail(),
            "security" => [
                "credentials" => [
                    "password" => $user->getPassword()
                ]
            ]
        ];


        $crawler = $this->webClient->request("PUT", "/api/login_check", content: json_encode($content));

        $response = $this->webClient->getResponse();

        $responseContent = json_decode($response->getContent(), true);

        print_r($responseContent);
    }

    public function testFunc_addUser(string $email, string $firstname, string $lastname, string $password)
    {
        $userRepository = $this->getService(UserRepository::class);
        $passwordHasher = $this->getService(UserPasswordHasherInterface::class);

        $newUser = new User($email, $firstname, $lastname);

        $hashedPassword = $passwordHasher->hashPassword(
            $newUser,
            $password
        );

        $newUser->setPassword($hashedPassword);

        $userRepository->add($newUser);
    }

    public function testFunc_addBook(string $title, string $description, string $ISBN, User $user)
    {
        $bookRepository = $this->getService(BookRepository::class);

        $newBook = new Book($title, $description, $ISBN, $user);

        $bookRepository->add($newBook);
    }

    public function testFunc_addOpinion(int $rating, string $description, string $author, string $email, Book $book)
    {
        $opinionRepository = $this->getService(OpinionRepository::class);

        $newOpinion = new Opinion($rating, $description, $author, $email, $book);

        $opinionRepository->add($newOpinion);
    }
}