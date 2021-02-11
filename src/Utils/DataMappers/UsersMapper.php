<?php

namespace App\Utils\DataMappers;

use App\Dto\UserDto;
use App\Entity\User;
use Exception;

/**
 * Data mapper for users
 *
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Utils\DataMappers
 */
class UsersMapper
{
    /**
     * Convert DTO to user entity
     *
     * @param UserDto $userDto
     *
     * @return User
     * @throws Exception
     */
    public function toEntity(UserDto $userDto): User
    {
        return (new User())
            ->setId($userDto->getId())
            ->setLogin($userDto->getLogin())
            ->setPassword($userDto->getPassword())
            ->setEmail($userDto->getEmail())
            ->setGender($userDto->getGender())
            ->setBirthday($userDto->getBirthday())
            ->setCreatedAt($userDto->getCreatedAt());
    }

    /**
     * Convert user entity to DTO
     *
     * @param User $user
     *
     * @return UserDto
     */
    public function toDto(User $user): UserDto
    {
        return (new UserDto())
            ->setId($user->getId())
            ->setLogin($user->getLogin())
            ->setPassword($user->getPassword())
            ->setEmail($user->getEmail())
            ->setGender($user->getGender())
            ->setBirthday($user->getBirthday())
            ->setRole($user->getRole())
            ->setStatus($user->getStatus())
            ->setCreatedAt($user->getCreatedAt());
    }
}
