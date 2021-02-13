<?php

namespace App\Service;

use App\Dto\ActivationLinkParams;
use App\Dto\UserDto;
use App\Entity\ActivationLink;
use App\Entity\User;
use App\Utils\DataMappers\UsersMapper;
use App\Utils\KafkaHelper;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectRepository;
use Exception;

/**
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Service
 */
class UsersService
{
    use KafkaHelper;

    /**
     * Entity manager interface
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UsersMapper
     */
    protected $mapper;

    /**
     * @var string
     */
    protected $kafkaHost;

    /**
     * @var string
     */
    protected $kafkaPort;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * Constructor
     *
     * @param string $kafkaHost
     * @param string $kafkaPort
     * @param EntityManagerInterface $em
     * @param UsersMapper $mapper
     */
    public function __construct(
        $kafkaHost = '',
        $kafkaPort = '',
        EntityManagerInterface $em,
        UsersMapper $mapper
    ) {
        $this->em = $em;
        $this->mapper = $mapper;
        $this->kafkaHost = $kafkaHost;
        $this->kafkaPort = $kafkaPort;
        $this->repository = $em->getRepository(User::class);
    }

    /**
     * @param UserDto $userDto
     *
     * @return UserDto
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function create(UserDto $userDto): UserDto
    {
        try {
            $producer = $this->configureProducer($this->kafkaHost, $this->kafkaPort);

            $this->em->beginTransaction();

            $user = $this->mapper->toEntity($userDto);
            $link = $this->createLink($user);
            $kafkaMessage = ['email' => $user->getEmail(), 'token' => $link->getToken()];

            $this->em->persist($user);
            $this->em->persist($link);
            $this->em->flush();

            $this->em->commit();

            $producer->send(
                [
                    [
                        'topic' => 'notification-activation-links', // todo make ENV
                        'key'   => '',
                        'value' => json_encode($kafkaMessage),
                    ],
                ]
            );

            return $this->mapper->toDto($user);
        } catch (Exception $e) {
            $this->em->rollback();

            throw $e;
        }
    }

    /**
     * @param ActivationLinkParams $params
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function activateUser(ActivationLinkParams $params)
    {
        try {
            $this->em->beginTransaction();

            $link = ($this->em->getRepository(ActivationLink::class))->findOneBy(['token' => $params->getToken()]);

            $link->getUser()->setStatus(User::STATUS_ON);
            $link->setIsActivated(true);
            $this->em->flush();

            $this->em->commit();
        } catch (Exception $e) {
            $this->em->rollback();

            throw $e;
        }
    }

    /**
     * Creates activation link
     *
     * @param User $user
     *
     * @return ActivationLink
     * @throws Exception
     */
    private function createLink(User $user): ActivationLink
    {
        return (new ActivationLink())
            ->setUser($user)
            ->setExpireAt(new DateTimeImmutable('now + 1 day', new DateTimeZone('UTC')))
            ->setToken(hash("sha256", rand()));
    }
}
