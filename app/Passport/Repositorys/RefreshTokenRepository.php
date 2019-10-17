<?php
/**
 * Created by PhpStorm.
 * User: leiyu
 * Date: 2018/4/23
 * Time: 17:38
 */

namespace App\Passport\Repositorys;


use App\Passport\Entitys\RefreshTokenEntity;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{

    /**
     * Creates a new refresh token
     *
     * @return RefreshTokenEntityInterface
     */
    public function getNewRefreshToken()
    {
        // TODO: Implement getNewRefreshToken() method.

        return new RefreshTokenEntity();
    }

    /**
     * Create a new refresh token_name.
     *
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        // TODO: Implement persistNewRefreshToken() method.
    }

    /**
     * Revoke the refresh token.
     *
     * @param string $tokenId
     */
    public function revokeRefreshToken($tokenId)
    {
        // TODO: Implement revokeRefreshToken() method.
    }

    /**
     * Check if the refresh token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        // TODO: Implement isRefreshTokenRevoked() method.

        return false;
    }
}
