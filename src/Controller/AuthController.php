<?php

namespace App\Controller;

use App\Annotation\AuthValidation;
use App\Entity\User;
use App\Exception\DataNotFoundException;
use App\Exception\InvalidJsonDataException;
use App\Model\DataNotFoundModel;
use App\Model\JsonDataInvalidModel;
use App\Query\RegisterQuery;
use App\Repository\UserRepository;
use App\Service\RequestServiceInterface;
use App\Tool\ResponseTool;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Response(
    response: 400,
    description: "JSON Data Invalid",
    content: new Model(type: JsonDataInvalidModel::class)
)]
#[OA\Response(
    response: 404,
    description: "Data not found",
    content: new Model(type: DataNotFoundModel::class)
)]
#[OA\Tag(name: "Register")]
class AuthController extends AbstractController
{
    /**
     * @param Request $request
     * @param RequestServiceInterface $requestServiceInterface
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $passwordHasher
     * @param LoggerInterface $endpointLogger
     * @return Response
     * @throws DataNotFoundException
     * @throws InvalidJsonDataException
     *
     * Endpoint odpowiadający za rejestrację użytkownika. Po podaniu odpowiednich danych w query sprawdza czy nie istnieje
     * już jakiś użytkownik z podanym mailem(Jeśli tak to rzuca 404), następnie czy podany hasła są identyczne(tu również jeśli nie to 404) i
     * na koniec tworzy endcję oraz hashuje podane hasło do odpowiedniej formy. Przy pomocy repository w systemie dodawane do bazy są encje.
     * W query natomiast są bardziej precyzyjne sprawdzenia danych które będą zmieniane na encje w bazie. Większość jest w regexie bo mocno
     * to uprawszcza i nie trzeba dodawać kilkunastu assercji.
     */
    #[Route('/api/register', name: 'app_register', methods: ["PUT"])]
    #[AuthValidation(checkAuthToken: false)]
    #[OA\Put(
        description: "Endpoint is used to register new user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: RegisterQuery::class),
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
    public function registration(
        Request                     $request,
        RequestServiceInterface     $requestServiceInterface,
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        LoggerInterface             $endpointLogger,
    ): Response
    {
        $registerQuery = $requestServiceInterface->getRequestBodyContent($request, RegisterQuery::class);

        if ($registerQuery instanceof RegisterQuery) {

            $userExists = $userRepository->findOneBy([
                "email" => $registerQuery->getEmail()
            ]);

            if ($userExists != null) {
                $endpointLogger->error('Email in system');
                throw new DataNotFoundException(["register.used.email"]);
            }

            if ($registerQuery->getPassword() != $registerQuery->getConfirmPassword()) {
                $endpointLogger->error('Passwords are not the same');
                throw new DataNotFoundException(["register.invalid.passwords"]);
            }

            $user = new User($registerQuery->getEmail(), $registerQuery->getFirstname(), $registerQuery->getLastname());

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $registerQuery->getPassword()
            );

            $user->setPassword($hashedPassword);

            $userRepository->add($user);

            return ResponseTool::getResponse(httpCode: 201);
        } else {
            $endpointLogger->error('Invalid query');
            throw new InvalidJsonDataException("register.invalid.query");
        }
    }

}
