<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;


interface ResponseExceptionInterface
{
    /**
     * Function which return response value when throw an error
     *
     * @return Response
     */
    public function getResponse(): Response;
}