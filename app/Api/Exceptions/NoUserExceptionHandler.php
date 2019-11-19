<?php


namespace App\Api\Exceptions;


use App\Api\ApiCode;
use App\Exceptions\NoUserException;
use Exception;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class NoUserExceptionHandler implements ExceptionHandlerInterface
{

    /**
     * If the exception handler is able to format a response for the provided exception,
     * then the implementation should return true.
     *
     * @param \Exception $e
     *
     * @return bool
     */
    public function manages(Exception $e)
    {
        return $e instanceof NoUserException;
    }

    /**
     * Handle the provided exception.
     *
     * @param \Exception $e
     *
     * @return \Tobscure\JsonApi\Exception\Handler\ResponseBag
     */
    public function handle(Exception $e)
    {
        // TODO: Implement handle() method.
        $status = 400;
        $error = [
            'status' => ApiCode::OAUTH_NO_BIND_USER,
            'code' => 'No bind user',
            'user' => $e->getUser()
        ];

        return new ResponseBag($status, [$error]);
    }
}
