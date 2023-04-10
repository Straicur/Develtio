<?php

namespace App\Exception;

use App\Model\NotAuthorizeModel;
use App\Tool\ResponseTool;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationException extends \Exception implements ResponseExceptionInterface
{
    public function getResponse(): Response
    {
        return ResponseTool::getResponse(new NotAuthorizeModel(), 401);
    }
}