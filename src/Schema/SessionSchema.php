<?php

namespace App\Schema;

use App\Dto\SessionDto;
use Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * JSON API schema for a session
 *
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Schema
 */
class SessionSchema extends SchemaProvider
{
    protected $resourceType = 'sessions';

    /**
     * @param SessionDto $resource
     *
     * @return string
     */
    public function getId($resource): string
    {
        return '';
    }

    /**
     * @param SessionDto $resource
     *
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'accessToken' => $resource->getAccessToken(),
            'expiresAt'   => $resource->getExpiresAt()->format(DATE_ATOM),
        ];
    }
}