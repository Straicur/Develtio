<?php

namespace App\Command;

use App\Entity\Opinion;
use App\Repository\BookRepository;
use App\Repository\OpinionRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add:book:opinion',
    description: 'Add book opinion',
)]
class AddBookOpinion extends Command
{
    private BookRepository $bookRepository;
    private OpinionRepository $opinionRepository;

    public function __construct(
        BookRepository    $bookRepository,
        OpinionRepository $opinionRepository
    )
    {
        $this->bookRepository = $bookRepository;
        $this->opinionRepository = $opinionRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('bookTitle', InputArgument::REQUIRED, 'Book title');
        $this->addArgument('rating', InputArgument::REQUIRED, 'Anonymous user rating');
        $this->addArgument('description', InputArgument::REQUIRED, 'Anonymous user description');
        $this->addArgument('author', InputArgument::REQUIRED, 'Anonymous user author');
        $this->addArgument('email', InputArgument::REQUIRED, 'Anonymous user email');
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

        $bookTitle = $input->getArgument("bookTitle");
        $rating = $input->getArgument("rating");
        $description = $input->getArgument("description");
        $author = $input->getArgument("author");
        $email = $input->getArgument("email");

        $book = $this->bookRepository->findOneBy([
            "title" => $bookTitle
        ]);

        if ($book == null) {
            $io->error('Cant find book');
            return Command::FAILURE;
        }

        $newOpinion = new Opinion($rating, $description, $author, $email, $book);

        $this->opinionRepository->add($newOpinion);

        $io->success('Opinion added');

        return Command::SUCCESS;
    }
}
