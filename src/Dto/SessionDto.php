<?php

namespace App\Dto;

use DateTimeInterface;
use Reva2\JsonApi\Annotations\ApiResource;
use Reva2\JsonApi\Annotations\Attribute;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Dto
 *
 * @ApiResource(name="sessions")
 */
class SessionDto
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Attribute()
     */
    protected $login;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Attribute()
     */
    protected $password;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var DateTimeInterface
     */
    protected $expiresAt;

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     *
     * @return SessionDto
     */
    public function setLogin(string $login): SessionDto
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return SessionDto
     */
    public function setPassword(string $password): SessionDto
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @param string|null $accessToken
     *
     * @return SessionDto
     */
    public function setAccessToken(?string $accessToken): SessionDto
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getExpiresAt(): ?DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * @param DateTimeInterface|null $expiresAt
     *
     * @return SessionDto
     */
    public function setExpiresAt(?DateTimeInterface $expiresAt): SessionDto
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}
