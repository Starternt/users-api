<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="activation_links")
 */
class ActivationLink
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=75)
     */
    protected $token;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    protected $expireAt;

    /**
     * Has link been activated or not
     *
     * @var bool
     * @ORM\Column(type="boolean", name="activated")
     */
    protected $isActivated = 0;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return ActivationLink
     */
    public function setId(string $id): ActivationLink
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return ActivationLink
     */
    public function setToken(string $token): ActivationLink
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getExpireAt(): DateTimeInterface
    {
        return $this->expireAt;
    }

    /**
     * @param DateTimeInterface $expireAt
     *
     * @return ActivationLink
     */
    public function setExpireAt(DateTimeInterface $expireAt): ActivationLink
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActivated(): bool
    {
        return $this->isActivated;
    }

    /**
     * @param bool $isActivated
     *
     * @return ActivationLink
     */
    public function setIsActivated(bool $isActivated): ActivationLink
    {
        $this->isActivated = $isActivated;

        return $this;
    }
}
