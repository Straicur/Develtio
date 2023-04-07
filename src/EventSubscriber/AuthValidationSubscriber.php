<?php

namespace App\EventSubscriber;

use App\Annotation\AuthValidation;
use App\Exception\AuthenticationException;
use App\Repository\AuthenticationTokenRepository;
use App\Repository\UserRepository;
use App\Serializer\SerializerInterface;
use App\Service\AuthorizedUserService;
use Doctrine\ORM\NonUniqueResultException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * AuthValidationSubscriber
 *
 */
class AuthValidationSubscriber implements EventSubscriberInterface
{

    private LoggerInterface $responseLogger;

    private LoggerInterface $requestLogger;

    private SerializerInterface $jsonSerializer;

    private TokenStorageInterface $storage;
    private UserRepository $userRepository;

    public function __construct(
        SerializerInterface   $jsonSerializer,
        LoggerInterface       $responseLogger,
        LoggerInterface       $requestLogger,
        TokenStorageInterface $storage,
        UserRepository        $userRepository
    )
    {
        $this->responseLogger = $responseLogger;
        $this->requestLogger = $requestLogger;
        $this->jsonSerializer = $jsonSerializer;
        $this->storage = $storage;
        $this->userRepository = $userRepository;
    }

    /**
     * @param ControllerEvent $event
     * @return void
     * @throws AuthenticationException
     */
    public function onControllerCall(ControllerEvent $event): void
    {
        $controller = $event->getController();
        $request = $event->getRequest();

        if (is_array($controller)) {
            $method = $controller[1];
            $controller = $controller[0];

            try {
                $controllerReflectionClass = new \ReflectionClass($controller);
                $reflectionMethod = $controllerReflectionClass->getMethod($method);
                $methodAttributes = $reflectionMethod->getAttributes(AuthValidation::class);

                if (count($methodAttributes) == 1) {
                    $authValidationAttribute = $methodAttributes[0]->newInstance();

                    if ($authValidationAttribute instanceof AuthValidation) {
                        if ($authValidationAttribute->isCheckAuthToken()) {
                            $authorizationHeaderField = $request->headers->get("authorization");

                            if ($authorizationHeaderField == null) {
                                throw new AuthenticationException();
                            } else {
                                $userEmail = $this->storage->getToken()->getUser()->getUserIdentifier();

                                $user = $this->userRepository->findOneBy([
                                    "email" => $userEmail
                                ]);

                                if ($user != null) {
                                    AuthorizedUserService::setAuthorizedUser($user);
                                } else {
                                    throw new AuthenticationException();
                                }
                            }
                        }

                    }
                }

            } catch (\ReflectionException $e) {
                //todo when class or method not exits
            } catch (NonUniqueResultException $e) {
                //todo reaction on error
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onControllerCall'
        ];
    }
}