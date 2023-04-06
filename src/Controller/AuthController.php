<?php

namespace App\Controller;

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
     * @return Response
     * @throws InvalidJsonDataException
     * @throws DataNotFoundException
     */
    #[Route('/api/register', name: 'app_register', methods: ["PUT"])]
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
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $registerQuery = $requestServiceInterface->getRequestBodyContent($request, RegisterQuery::class);

        if ($registerQuery instanceof RegisterQuery) {

            $userExists = $userRepository->findOneBy([
                "email" => $registerQuery->getEmail()
            ]);

            if ($userExists != null) {
                throw new DataNotFoundException(["register.used.email"]);
            }

            if ($registerQuery->getPassword() != $registerQuery->getConfirmPassword()) {
                throw new DataNotFoundException(["register.invalid.passwords"]);
            }

            $user = new User($registerQuery->getEmail(), $registerQuery->getFirstname(), $registerQuery->getLastname());

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $registerQuery->getPassword()
            );

            $user->setPassword($hashedPassword);

            $userRepository->add($user);

            return ResponseTool::getResponse();
        } else {
            throw new InvalidJsonDataException("register.invalid.query");
        }
    }

}
