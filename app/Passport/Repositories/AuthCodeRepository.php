<?php
/**
 * Created by PhpStorm.
 * User: leiyu
 * Date: 2018/4/23
 * Time: 17:36
 */

namespace App\Passport\Repositories;


use App\Passport\Entities\AuthCodeEntity;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{

    /**
     * Creates a new AuthCode
     *
     * @return AuthCodeEntityInterface
     */
    public function getNewAuthCode()
    {
        // TODO: Implement getNewAuthCode() method.

        return new AuthCodeEntity();
    }

    /**
     * Persists a new auth code to permanent storage.
     *
     * @param AuthCodeEntityInterface $authCodeEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        // TODO: Implement persistNewAuthCode() method.
    }

    /**
     * Revoke an auth code.
     *
     * @param string $codeId
     */
    public function revokeAuthCode($codeId)
    {
        // TODO: Implement revokeAuthCode() method.
    }

    /**
     * Check if the auth code has been revoked.
     *
     * @param string $codeId
     *
     * @return bool Return true if this code has been revoked
     */
    public function isAuthCodeRevoked($codeId)
    {
        // TODO: Implement isAuthCodeRevoked() method.
        return false;
    }
}
