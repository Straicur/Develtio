<?php

namespace App\Controller;

use App\Annotation\AuthValidation;
use App\Entity\Opinion;
use App\Enums\PageLimit;
use App\Exception\DataNotFoundException;
use App\Exception\InvalidJsonDataException;
use App\Model\AuthorModel;
use App\Model\OpinionModel;
use App\Model\OpinionSuccessModel;
use App\Model\UserBookModel;
use App\Model\UserBooksSuccessModel;
use App\Model\UserBookSuccessModel;
use App\Query\UserBookDetailQuery;
use App\Query\UserBookOpinionAddQuery;
use App\Repository\BookRepository;
use App\Repository\OpinionRepository;
use App\Service\RequestServiceInterface;
use App\Tool\ResponseTool;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "User")]
class UserController extends AbstractController
{
    /**
     * @param BookRepository $bookRepository
     * @param int $page
     * @param string|null $title
     * @param string|null $description
     * @return Response
     */
    #[Route('/api/user/books/{page}', name: 'app_user_books', methods: ["GET"])]
    #[AuthValidation(checkAuthToken: false)]
    #[OA\Get(
        description: "Endpoint is used to get list of books in system by not logged user",
        requestBody: new OA\RequestBody(),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new Model(type: UserBooksSuccessModel::class),
            )
        ]
    )]
    public function userBooks(
        BookRepository $bookRepository,
        int            $page,
        ?string         $title = null,
        ?string         $description = null,
    ): Response
    {
        //todo ten get paratemer jest do przemyślenia {?title}{?description}
        $successModel = new UserBooksSuccessModel();

        $userBooks = $bookRepository->findBooksForUser($title, $description);

        $minResult = $page * PageLimit::LIMIT->value;
        $maxResult = PageLimit::LIMIT->value + $minResult;

        foreach ($userBooks as $index => $book) {
            if ($index < $minResult) {
                continue;
            } elseif ($index < $maxResult) {
                $author = $book->getUser();
                $successModel->addBook(
                    new UserBookModel(
                        $book->getId(),
                        $book->getTitle(),
                        $book->getDescription(),
                        $book->getISBN(),
                        $book->getDateAdded(),
                        new AuthorModel(
                            $author->getFirstname(),
                            $author->getLastname()
                        )
                    )
                );
            } else {
                break;
            }
        }

        $successModel->setPage($page);

        $successModel->setMaxPage(ceil(count($userBooks) / PageLimit::LIMIT->value));

        return ResponseTool::getResponse($successModel);
    }

    /**
     * @param Request $request
     * @param RequestServiceInterface $requestServiceInterface
     * @param BookRepository $bookRepository
     * @param OpinionRepository $opinionRepository
     * @return Response
     * @throws DataNotFoundException
     * @throws InvalidJsonDataException
     */
    #[Route('/api/user/book/detail', name: 'app_user_book_detail', methods: ["POST"])]
    #[AuthValidation(checkAuthToken: false)]
    #[OA\Post(
        description: "Endpoint is used to get details of book by not logged user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: UserBookDetailQuery::class),
                type: "object"
            ),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new Model(type: UserBookSuccessModel::class),
            )
        ]
    )]
    public function userBookDetail(
        Request                 $request,
        RequestServiceInterface $requestServiceInterface,
        BookRepository          $bookRepository,
        OpinionRepository       $opinionRepository
    ): Response
    {
        $userBookDetailQuery = $requestServiceInterface->getRequestBodyContent($request, UserBookDetailQuery::class);

        if ($userBookDetailQuery instanceof UserBookDetailQuery) {

            $book = $bookRepository->findOneBy([
                "id" => $userBookDetailQuery->getBookId()
            ]);

            if ($book == null) {
                throw new DataNotFoundException(["user.book.detail.cant.find.book"]);
            }

            $opinions = $opinionRepository->findBy([
                "book" => $book->getId()
            ]);

            $successModel = new UserBookSuccessModel(
                $book->getId(),
                $book->getTitle(),
                $book->getDescription(),
                $book->getISBN(),
                $book->getDateAdded()
            );
            foreach ($opinions as $opinion) {
                $successModel->addOpinion(
                    new OpinionModel(
                        $opinion->getId(),
                        $opinion->getAuthor(),
                        $opinion->getDescription(),
                        $opinion->getEmail(),
                        $opinion->getRating(),
                        $opinion->getDateAdded()
                    )
                );
            }

            return ResponseTool::getResponse($successModel);
        } else {
            throw new InvalidJsonDataException("user.book.detail.invalid.query");
        }
    }

    /**
     * @param Request $request
     * @param RequestServiceInterface $requestServiceInterface
     * @param BookRepository $bookRepository
     * @param OpinionRepository $opinionRepository
     * @return Response
     * @throws DataNotFoundException
     * @throws InvalidJsonDataException
     */
    #[Route('/api/user/book/opinion/add', name: 'app_user_book_opinion_add', methods: ["PUT"])]
    #[AuthValidation(checkAuthToken: false)]
    #[OA\Put(
        description: "Endpoint is used to add opinion by not logged user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: UserBookOpinionAddQuery::class),
                type: "object"
            ),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new Model(type: OpinionSuccessModel::class),
            )
        ]
    )]
    public function userBookOpinionAdd(
        Request                 $request,
        RequestServiceInterface $requestServiceInterface,
        BookRepository          $bookRepository,
        OpinionRepository       $opinionRepository
    ): Response
    {
        $userBookOpinionAddQuery = $requestServiceInterface->getRequestBodyContent($request, UserBookOpinionAddQuery::class);

        if ($userBookOpinionAddQuery instanceof UserBookOpinionAddQuery) {

            $book = $bookRepository->findOneBy([
                "id" => $userBookOpinionAddQuery->getBookId()
            ]);

            if ($book == null) {
                throw new DataNotFoundException(["user.book.opinion.cant.find.book"]);
            }

            $newOpinion = new Opinion(
                $userBookOpinionAddQuery->getRating(),
                $userBookOpinionAddQuery->getDescription(),
                $userBookOpinionAddQuery->getAuthor(),
                $userBookOpinionAddQuery->getEmail(),
                $book
            );

            $opinionRepository->add($newOpinion);

            $successModel = new OpinionSuccessModel(
                $newOpinion->getId(),
                $newOpinion->getAuthor(),
                $newOpinion->getDescription(),
                $newOpinion->getEmail(),
                $newOpinion->getRating(),
                $newOpinion->getDateAdded(),
                new UserBookModel(
                    $book->getId(),
                    $book->getTitle(),
                    $book->getDescription(),
                    $book->getISBN(),
                    $book->getDateAdded(),
                    new AuthorModel(
                        $book->getUser()->getFirstname(),
                        $book->getUser()->getLastname()
                    )
                )
            );

            return ResponseTool::getResponse($successModel, 201);
        } else {
            throw new InvalidJsonDataException("user.book.opinion.add.invalid.query");
        }
    }
}

