<?php

namespace App\Controller;

use App\Annotation\AuthValidation;
use App\Entity\Book;
use App\Enums\PageLimit;
use App\Exception\DataNotFoundException;
use App\Exception\InvalidJsonDataException;
use App\Model\AuthorBookModel;
use App\Model\AuthorBooksSuccessModel;
use App\Model\BookSuccessModel;
use App\Query\AuthorBookAddQuery;
use App\Query\AuthorBookDeleteQuery;
use App\Query\AuthorBookEditQuery;
use App\Repository\BookRepository;
use App\Repository\OpinionRepository;
use App\Service\AuthorizedUserServiceInterface;
use App\Service\RequestServiceInterface;
use App\Tool\ResponseTool;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "Author")]
class AuthorController extends AbstractController
{

    /**
     * @param AuthorizedUserServiceInterface $authorizedUserService
     * @param BookRepository $bookRepository
     * @param int $page
     * @return Response
     *
     * Endpoint ten służy za pobieranie książek zalogowanego użytkownika. Dodałem do niego jeszcze w endpoincie parameter
     * page który jednak może być przydatny przy pobieraniu listy podzielonej na strony. Do przechowywania ilości rekordów
     * na stronę wykorzystałem Enuma. Do dzielenia na strony wykorzystałem podejście phpowe które sprawdza to przy pomocy
     * pętli. Można to oczywiście też zrobić przy pomocy zapytania do bazy i w nim odpowiednio ustawić limit oraz offset.
     * Wykorzystałem to podejście ponieważ jest pewne i przy tak małej ilości danych nie wpłynie na wydajność. Widać też
     * jak wykorzystuje się tu modele do których dodaje się dane i na koniec przekazuje do toola serializującego i wysyłającego
     * reqsponse. dokumentacja nemlio również wykorzystuje te obiekty i pod linkiem endpointu widać co zwróci i w jakim formacie.
     * Widać też wykorzystanie stworzonej adnitacji, wstrzyknięty zostaje też serwis do którego zapisywany jest użytkownik,
     * można też pobierać statycznej metody tego użytkownika.
     */
    #[Route('/api/author/books/{page}', name: 'app_author_books', methods: ["GET"])]
    #[AuthValidation(checkAuthToken: true)]
    #[OA\Get(
        description: "Endpoint is used to returning logged user books",
        requestBody: new OA\RequestBody(),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new Model(type: AuthorBooksSuccessModel::class),
            )
        ]
    )]
    public function authorBooks(
        AuthorizedUserServiceInterface $authorizedUserService,
        BookRepository                 $bookRepository,
        int                            $page
    ): Response
    {
        $successModel = new AuthorBooksSuccessModel();

        $userBooks = $bookRepository->findBy([
            "user" => $authorizedUserService::getAuthorizedUser()->getId()
        ]);

        $minResult = $page * PageLimit::LIMIT->value;
        $maxResult = PageLimit::LIMIT->value + $minResult;

        foreach ($userBooks as $index => $book) {
            if ($index < $minResult) {
                continue;
            } elseif ($index < $maxResult) {
                $successModel->addBook(
                    new AuthorBookModel(
                        $book->getId(),
                        $book->getTitle(),
                        $book->getDescription(),
                        $book->getISBN(),
                        $book->getDateAdded()
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
     * @param AuthorizedUserServiceInterface $authorizedUserService
     * @param BookRepository $bookRepository
     * @param LoggerInterface $endpointLogger
     * @return Response
     * @throws InvalidJsonDataException
     *
     * Endpoint służący dod dodawania książki przez zalogowanego użytkownika do jego listy. W query tego endpointa regex
     * ISBN jest utworzony tak aby przyjmował go w różnym formacie może go przyjąć jako prosty ciag 875676457645 lub jako
     * rozdzielony 890-4324-4324-43 przy pobraniu jego wartości jest implodowany. Na koniec zwraca dodaną encję.
     */
    #[Route('/api/author/book/add', name: 'app_author_book_add', methods: ["PUT"])]
    #[AuthValidation(checkAuthToken: true)]
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
                content: new Model(type: BookSuccessModel::class),
            )
        ]
    )]
    public function authorBookAdd(
        Request                        $request,
        RequestServiceInterface        $requestServiceInterface,
        AuthorizedUserServiceInterface $authorizedUserService,
        BookRepository                 $bookRepository,
        LoggerInterface                $endpointLogger
    ): Response
    {
        $authorBookAddQuery = $requestServiceInterface->getRequestBodyContent($request, AuthorBookAddQuery::class);

        if ($authorBookAddQuery instanceof AuthorBookAddQuery) {

            $user = $authorizedUserService::getAuthorizedUser();

            $newBook = new Book($authorBookAddQuery->getTitle(), $authorBookAddQuery->getDescription(), $authorBookAddQuery->getISBN(), $user);

            $bookRepository->add($newBook);

            $successModel = new BookSuccessModel(
                $newBook->getId(),
                $newBook->getTitle(),
                $newBook->getDescription(),
                $newBook->getISBN(),
                $newBook->getDateAdded()
            );

            return ResponseTool::getResponse($successModel, 201);
        } else {
            $endpointLogger->error('Invalid query');
            throw new InvalidJsonDataException("author.book.add.invalid.query");
        }
    }

    /**
     * @param Request $request
     * @param RequestServiceInterface $requestServiceInterface
     * @param AuthorizedUserServiceInterface $authorizedUserService
     * @param BookRepository $bookRepository
     * @param LoggerInterface $endpointLogger
     * @return Response
     * @throws DataNotFoundException
     * @throws InvalidJsonDataException
     *
     * Endpoint ten służy do edycji podanej w requestcie za pomocą Id książki. Przy pomocy zapytania do bazy sprawdzane jest
     * od razu czy zalogowany użytkownik jest właścicielem, jeśli nie to wyrzuci błąd 404. Encja jest tu edytowana i na koniec zwrócona.
     */
    #[Route('/api/author/book/edit', name: 'app_author_book_edit', methods: ["PATCH"])]
    #[AuthValidation(checkAuthToken: true)]
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
                content: new Model(type: BookSuccessModel::class),
            )
        ]
    )]
    public function authorBookEdit(
        Request                        $request,
        RequestServiceInterface        $requestServiceInterface,
        AuthorizedUserServiceInterface $authorizedUserService,
        BookRepository                 $bookRepository,
        LoggerInterface                $endpointLogger
    ): Response
    {
        $authorBookEditQuery = $requestServiceInterface->getRequestBodyContent($request, AuthorBookEditQuery::class);

        if ($authorBookEditQuery instanceof AuthorBookEditQuery) {

            $user = $authorizedUserService::getAuthorizedUser();

            $book = $bookRepository->findOneBy([
                "id" => $authorBookEditQuery->getBookId(),
                "user" => $user->getId()
            ]);

            if ($book == null) {
                $endpointLogger->error('Cant find user book');
                throw new DataNotFoundException(["author.book.edit.cant.find.book"]);
            }

            $book->setTitle($authorBookEditQuery->getTitle());
            $book->setDescription($authorBookEditQuery->getDescription());

            $bookRepository->add($book);

            $successModel = new BookSuccessModel(
                $book->getId(),
                $book->getTitle(),
                $book->getDescription(),
                $book->getISBN(),
                $book->getDateAdded()
            );

            return ResponseTool::getResponse($successModel);
        } else {
            $endpointLogger->error('Invalid query');
            throw new InvalidJsonDataException("author.book.edit.invalid.query");
        }
    }

    /**
     * @param Request $request
     * @param RequestServiceInterface $requestServiceInterface
     * @param AuthorizedUserServiceInterface $authorizedUserService
     * @param BookRepository $bookRepository
     * @param OpinionRepository $opinionRepository
     * @param LoggerInterface $endpointLogger
     * @return Response
     * @throws DataNotFoundException
     * @throws InvalidJsonDataException
     *
     * Encja służy do usuwania książki użytkownika. W zapytaniu do bazy sprawdza czy do niego należy oraz po tym sprawdza
     * czy do tej książki została dodana jakaś opinia(jeśli tak to rzuca 404). Jeśli trzeba by było usuwać również opinie
     * to zastować należałoby cascadowe usuwanie.
     */
    #[Route('/api/author/book/delete', name: 'app_author_book_delete', methods: ["DELETE"])]
    #[AuthValidation(checkAuthToken: true)]
    #[OA\Delete(
        description: "Endpoint is used to delete author book",
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
        Request                        $request,
        RequestServiceInterface        $requestServiceInterface,
        AuthorizedUserServiceInterface $authorizedUserService,
        BookRepository                 $bookRepository,
        OpinionRepository              $opinionRepository,
        LoggerInterface                $endpointLogger
    ): Response
    {
        $authorBookDeleteQuery = $requestServiceInterface->getRequestBodyContent($request, AuthorBookDeleteQuery::class);

        if ($authorBookDeleteQuery instanceof AuthorBookDeleteQuery) {

            $user = $authorizedUserService::getAuthorizedUser();

            $book = $bookRepository->findOneBy([
                "id" => $authorBookDeleteQuery->getBookId(),
                "user" => $user->getId()
            ]);

            if ($book == null) {
                $endpointLogger->error('Cant find user book');
                throw new DataNotFoundException(["author.book.delete.cant.find.book"]);
            }

            if ($opinionRepository->bookHasOpinions($book)) {
                $endpointLogger->error('Book has opinions');
                throw new DataNotFoundException(["author.book.delete.book.has.opinions"]);
            }

            $bookRepository->remove($book);

            return ResponseTool::getResponse();
        } else {
            $endpointLogger->error('Invalid query');
            throw new InvalidJsonDataException("author.book.delete.invalid.query");
        }
    }
}