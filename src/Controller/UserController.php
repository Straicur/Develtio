<?php

namespace App\Controller;

use App\Exception\DataNotFoundException;
use App\Exception\InvalidJsonDataException;
use App\Query\UserBookDetailQuery;
use App\Query\UserBookOpinionAddQuery;
use App\Repository\BookRepository;
use App\Repository\OpinionRepository;
use App\Repository\UserRepository;
use App\Service\RequestServiceInterface;
use App\Tool\ResponseTool;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "User")]
class UserController extends AbstractController
{
    /**
     * @param Request $request
     * @param RequestServiceInterface $requestServiceInterface
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     * @throws InvalidJsonDataException
     * @throws DataNotFoundException
     */
    #[Route('/api/user/books/{page}', name: 'app_user_books', methods: ["GET"])]
    #[OA\Get(
        description: "Endpoint is used to ",
        requestBody: new OA\RequestBody(),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
            )
        ]
    )]
    public function userBooks(
        Request                 $request,
        RequestServiceInterface $requestServiceInterface,
        BookRepository          $bookRepository,
        int $page
    ): Response
    {
        // Wykorzystaj enuma
        return ResponseTool::getResponse();

    }

    /**
     * @param Request $request
     * @param RequestServiceInterface $requestServiceInterface
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     * @throws InvalidJsonDataException
     * @throws DataNotFoundException
     */
    #[Route('/api/user/book/detail', name: 'app_user_book_detail', methods: ["POST"])]
    #[OA\Post(
        description: "Endpoint is used to ",
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
            )
        ]
    )]
    public function userBookDetail(
        Request                 $request,
        RequestServiceInterface $requestServiceInterface,
        BookRepository          $bookRepository,
    ): Response
    {
        $userBookDetailQuery = $requestServiceInterface->getRequestBodyContent($request, UserBookDetailQuery::class);

        if ($userBookDetailQuery instanceof UserBookDetailQuery) {

            return ResponseTool::getResponse();
        } else {
            throw new InvalidJsonDataException("user.book.detail.invalid.query");
        }
    }

    /**
     * @param Request $request
     * @param RequestServiceInterface $requestServiceInterface
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     * @throws InvalidJsonDataException
     * @throws DataNotFoundException
     */
    #[Route('/api/user/book/opinion/add', name: 'app_user_book_opinion_add', methods: ["PUT"])]
    #[OA\Put(
        description: "Endpoint is used to ",
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

            return ResponseTool::getResponse(null, 201);
        } else {
            throw new InvalidJsonDataException("user.book.opinion.add.invalid.query");
        }
    }
}

