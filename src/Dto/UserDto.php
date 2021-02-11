<?php

namespace App\Dto;

use App\Entity\User;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Ramsey\Uuid\Uuid;
use Reva2\JsonApi\Annotations\ApiResource;
use Reva2\JsonApi\Annotations\Attribute;
use Reva2\JsonApi\Annotations\Id;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\UserLogin;
use App\Validator\UserEmail;

/**
 * User DTO
 *
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Dto
 *
 * @ApiResource(name="users")
 * @UserLogin()
 * @UserEmail()
 */
class UserDto
{
    /**
     * @var string
     * @Id()
     */
    protected $id;

    /**
     * @var string
     * @Assert\Length(min=3, max=20)
     * @Assert\NotNull()
     * @Attribute()
     */
    protected $login;

    /**
     * @var string
     * @Assert\Length(min=5, max=50)
     * @Assert\NotNull()
     * @Attribute()
     */
    protected $password;

    /**
     * @var string
     * @Assert\Length(min=5, max=100)
     * @Assert\NotNull()
     * @Attribute()
     */
    protected $email;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     * @Assert\Choice(choices={"male","female","undefined"})
     * @Attribute()
     */
    protected $gender = User::GENDER_UNDEFINED;

    /**
     * @var DateTimeInterface
     * @Attribute(type="DateTime<Y-m-d>")
     * @Assert\Type(type="\DateTimeInterface")
     */
    protected $birthday;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var DateTimeInterface
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $role;

    /**
     * PostDto constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return UserDto
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * @return UserDto
     */
    public function setLogin(string $login): UserDto
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
     * @return UserDto
     */
    public function setPassword(string $password): UserDto
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return UserDto
     */
    public function setEmail(string $email): UserDto
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return UserDto
     */
    public function setStatus(string $status): UserDto
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return UserDto
     */
    public function setGender(string $gender): UserDto
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getBirthday(): ?DateTimeInterface
    {
        return $this->birthday;
    }

    /**
     * @param DateTimeInterface|null $birthday
     *
     * @return UserDto
     */
    public function setBirthday(?DateTimeInterface $birthday): UserDto
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     *
     * @return UserDto
     */
    public function setImage(?string $image): UserDto
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     *
     * @return UserDto
     */
    public function setCreatedAt(DateTimeInterface $createdAt): UserDto
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string $role
     *
     * @return UserDto
     */
    public function setRole(string $role): UserDto
    {
        $this->role = $role;

        return $this;
    }
}