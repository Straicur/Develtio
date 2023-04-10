<?php

namespace App\Command;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add:user:book',
    description: 'Add user book',
)]
class AddUserBookCommand extends Command
{
    private BookRepository $bookRepository;
    private UserRepository $userRepository;
    private LoggerInterface $cmdLogger;

    public function __construct(
        BookRepository  $bookRepository,
        UserRepository  $userRepository,
        LoggerInterface $cmdLogger
    )
    {
        $this->bookRepository = $bookRepository;
        $this->userRepository = $userRepository;
        $this->cmdLogger = $cmdLogger;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('userEmail', InputArgument::REQUIRED, 'User email');
        $this->addArgument('title', InputArgument::REQUIRED, 'Book title');
        $this->addArgument('description', InputArgument::REQUIRED, 'Book description');
        $this->addArgument('ISBN', InputArgument::REQUIRED, 'Book ISBN');
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

        $userEmail = $input->getArgument("userEmail");
        $title = $input->getArgument("title");
        $description = $input->getArgument("description");
        $ISBN = $input->getArgument("ISBN");

        $user = $this->userRepository->findOneBy([
            "email" => $userEmail
        ]);

        if ($user == null) {
            $io->error('Cant find user');
            return Command::FAILURE;
        }

        $newBook = new Book($title, $description, $ISBN, $user);

        $this->bookRepository->add($newBook);

        $this->cmdLogger->info('Book added Id:' . $newBook->getId());

        $io->success('Book added');

        return Command::SUCCESS;
    }
}
