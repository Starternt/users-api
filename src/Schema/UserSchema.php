<?php

namespace App\Schema;

use App\Dto\UserDto;
use Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * JSON API schema for a user
 *
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Schema
 */
class UserSchema extends SchemaProvider
{
    protected $resourceType = 'users';

    /**
     * @param UserDto $resource
     *
     * @return string
     */
    public function getId($resource): string
    {
        return (string)$resource->getId();
    }

    /**
     * @param UserDto $resource
     *
     * @return array
     */
    public function getAttributes($resource): array
    {
        $birthday = $resource->getBirthday();
        if (null !== $birthday) {
            $birthday = $birthday->format('Y-m-d');
        }

        $createdAt = $resource->getCreatedAt()->format(DATE_ATOM);

        return [
            'login'     => $resource->getLogin(),
            'email'     => $resource->getEmail(),
            'status'    => $resource->getStatus(),
            'gender'    => $resource->getGender(),
            'birthday'  => $birthday,
            'createdAt' => $createdAt,
            'role'      => $resource->getRole(),
        ];
    }
}