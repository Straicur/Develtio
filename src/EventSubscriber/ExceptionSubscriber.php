<?php

namespace App\EventSubscriber;

use App\Exception\DataNotFoundException;
use App\Exception\ResponseExceptionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * EventSubscriber ten jest wykorzystywany do wychwytywania NotFoundHttpExceptions i parsowania go na odpwoiednio staaworzony exception
 * Jest to bardziej przykład i można by go rozszerzyć aby wyłapytał dużo większą ilość exceptions
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ResponseExceptionInterface) {

            $event->setResponse($exception->getResponse());
        } else {

            switch ($exception::class) {
                case NotFoundHttpException::class:
                {
                    $notFoundException = new DataNotFoundException([$exception->getMessage()]);

                    $event->setResponse($notFoundException->getResponse());
                    break;
                }
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
