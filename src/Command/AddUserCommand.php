<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:add:user',
    description: 'Add user',
)]
class AddUserCommand extends Command
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private LoggerInterface $cmdLogger;

    public function __construct(
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        LoggerInterface             $cmdLogger
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->cmdLogger = $cmdLogger;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('firstname', InputArgument::REQUIRED, 'User firstname');
        $this->addArgument('lastname', InputArgument::REQUIRED, 'User lastname');
        $this->addArgument('email', InputArgument::REQUIRED, 'User e-mail address');
        $this->addArgument('password', InputArgument::REQUIRED, 'User password');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $firstname = $input->getArgument("firstname");
        $lastname = $input->getArgument("lastname");
        $email = $input->getArgument("email");
        $password = md5($input->getArgument("password"));

        $userExists = $this->userRepository->findOneBy([
            "email" => $email
        ]);

        if ($userExists != null) {
            $io->error('User with this email exists');
            return Command::FAILURE;
        }

        $newUser = new User($email, $firstname, $lastname);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $newUser,
            $password
        );

        $newUser->setPassword($hashedPassword);

        $this->userRepository->add($newUser);

        $this->cmdLogger->info('User added Id:' . $newUser->getId());

        $io->success('User added');

        return Command::SUCCESS;
    }
}
