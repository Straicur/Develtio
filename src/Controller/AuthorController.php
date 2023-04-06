<?php

namespace App\Controller;

use App\Exception\DataNotFoundException;
use App\Exception\InvalidJsonDataException;
use App\Query\AuthorBookAddQuery;
use App\Query\AuthorBookDeleteQuery;
use App\Query\AuthorBookEditQuery;
use App\Repository\BookRepository;
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

#[OA\Tag(name: "Author")]
class AuthorController extends AbstractController
{

    /**
     * @param Request $request
     * @param RequestServiceInterface $requestServiceInterface
     * @param BookRepository $bookRepository
     * @return Response
     */
    #[Route('/api/author/books', name: 'app_author_books', methods: ["GET"])]
    #[OA\Get(
        description: "Endpoint is used to returning logged user books",
        requestBody: new OA\RequestBody(),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
            )
        ]
    )]
    public function authorBooks(
        Request                 $request,
        RequestServiceInterface $requestServiceInterface,
        BookRepository          $bookRepository,
    ): Response
    {

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
    #[Route('/api/author/book/add', name: 'app_author_book_add', methods: ["PUT"])]
    #[OA\Put(
        description: "Endpoint is used to add new author book",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: AuthorBookAddQuery::class),
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
    public function authorBookAdd(
        Request                 $request,
        RequestServiceInterface $requestServiceInterface,
        BookRepository          $bookRepository,
    ): Response
    {
        $authorBookAddQuery = $requestServiceInterface->getRequestBodyContent($request, AuthorBookAddQuery::class);

        if ($authorBookAddQuery instanceof AuthorBookAddQuery) {
            // Zwraca dodaną książkę i status 201
            return ResponseTool::getResponse(null, 201);
        } else {
            throw new InvalidJsonDataException("author.book.add.invalid.query");
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
    #[Route('/api/author/book/edit', name: 'app_author_book_edit', methods: ["PATCH"])]
    #[OA\Patch(
        description: "Endpoint is used to edit author book",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: AuthorBookEditQuery::class),
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
    public function authorBookEdit(
        Request                 $request,
        RequestServiceInterface $requestServiceInterface,
        BookRepository          $bookRepository,
    ): Response
    {
        $authorBookEditQuery = $requestServiceInterface->getRequestBodyContent($request, AuthorBookEditQuery::class);

        if ($authorBookEditQuery instanceof AuthorBookEditQuery) {

            return ResponseTool::getResponse();
        } else {
            throw new InvalidJsonDataException("author.book.edit.invalid.query");
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
    #[Route('/api/author/book/delete', name: 'app_author_book_delete', methods: ["DELETE"])]
    #[OA\Delete(
        description: "Endpoint is used to ",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: AuthorBookDeleteQuery::class),
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
    public function authorBookDelete(
        Request                 $request,
        RequestServiceInterface $requestServiceInterface,
        BookRepository          $bookRepository,
    ): Response
    {
        $authorBookDeleteQuery = $requestServiceInterface->getRequestBodyContent($request, AuthorBookDeleteQuery::class);

        if ($authorBookDeleteQuery instanceof AuthorBookDeleteQuery) {

            return ResponseTool::getResponse();
        } else {
            throw new InvalidJsonDataException("author.book.delete.invalid.query");
        }
    }
}

