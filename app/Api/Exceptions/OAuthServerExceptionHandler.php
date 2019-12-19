<?php


namespace App\Api\Exceptions;


use App\Api\ApiCode;
use Exception;
use League\OAuth2\Server\Exception\OAuthServerException;
use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;
use Zend\Diactoros\Response;

class OAuthServerExceptionHandler implements ExceptionHandlerInterface
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
        return $e instanceof OAuthServerException;
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

        $response = $e->generateHttpResponse(new Response());

        $error = [
            'status' => $e->getHttpStatusCode(),
            'code' => $e->getErrorType(),
            'detail' => json_decode($response->getBody(), true)
        ];

        return new ResponseBag($e->getHttpStatusCode(), [$error]);
    }
}
