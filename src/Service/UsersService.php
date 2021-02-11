<?php

namespace App\Service;

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
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Service for posts
 *
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
     * Event dispatcher
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var LoggerInterface
     */
    protected $logger;

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
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface $logger
     * @param UsersMapper $mapper
     */
    public function __construct(
        $kafkaHost = '',
        $kafkaPort = '',
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger,
        UsersMapper $mapper
    ) {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
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
            $link = $this->createLink();
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
            // todo add logging
            $this->em->rollback();

            throw $e;
        }
    }

    /**
     * Creates activation link
     *
     * @return ActivationLink
     * @throws Exception
     */
    private function createLink(): ActivationLink
    {
        return (new ActivationLink())
            ->setExpireAt(new DateTimeImmutable('now + 1 day', new DateTimeZone('UTC')))
            ->setToken(hash("sha256", rand()));
    }
}
