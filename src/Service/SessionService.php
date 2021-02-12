<?php

namespace App\Service;

use App\Dto\SessionDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Utils\DataMappers\UsersMapper;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectRepository;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Service
 */
class SessionService
{
    /**
     * Entity manager interface
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ObjectRepository
     */
    protected $usersRepository;

    /**
     * @var JWTEncoderInterface
     */
    protected $jwtEncoder;

    /**
     * Constructor
     *
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @param JWTEncoderInterface $encoder
     */
    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger,
        JWTEncoderInterface $encoder
    ) {
        $this->em = $em;
        $this->logger = $logger;
        $this->usersRepository = $em->getRepository(User::class);
        $this->jwtEncoder = $encoder;
    }

    /**
     * @param SessionDto $sessionDto
     *
     * @return SessionDto
     * @throws JWTEncodeFailureException
     */
    public function createSession(SessionDto $sessionDto): SessionDto
    {
        try {
            /** @var User $user */
            $user = $this->usersRepository->findOneBy(['login' => $sessionDto->getLogin()]);

            $this->checkUserStatus($user, $sessionDto->getPassword());

            $token = $this->jwtEncoder->encode(['login' => $user->getLogin(), 'exp' => time() + 3600]);

            $sessionDto
                ->setAccessToken($token)
                ->setExpiresAt(new DateTimeImmutable('now + 1 hour', new DateTimeZone('UTC')));

            return $sessionDto;
        } catch (Exception $e) {
            $this->em->rollback();

            throw $e;
        }
    }

    /**
     * @param string $title
     *
     * @return JsonApiException
     */
    protected function throwException($title = ''): JsonApiException
    {
        $error = new Error(
            rand(),
            null,
            Response::HTTP_UNAUTHORIZED,
            null,
            $title
        );

        return new JsonApiException($error, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param User|null $user
     * @param string $password
     */
    private function checkUserStatus(?User $user, string $password)
    {
        if (null === $user) {
            throw $this->throwException(sprintf('Incorrect credentials'));
        }

        if ($user->getStatus() !== User::STATUS_ON) {
            throw $this->throwException('Incorrect credentials');
        }

        if (!password_verify($password, $user->getPassword())) {
            throw $this->throwException('Incorrect credentials');
        }
    }
}
