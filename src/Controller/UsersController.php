<?php

namespace App\Controller;

use App\Dto\UserDto;
use App\Service\UsersService;
use App\Utils\JsonApi\JsonApiErrorsTrait;
use Exception;
use Psr\Log\LoggerInterface;
use Reva2\JsonApi\Annotations\ApiRequest;
use Reva2\JsonApi\Contracts\Services\JsonApiServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller that implements users API
 *
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Controller
 */
class UsersController extends JsonApiController
{
    use JsonApiErrorsTrait;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var UsersService
     */
    protected $service;

    /**
     * @param JsonApiServiceInterface $jsonApiService
     * @param LoggerInterface $logger
     * @param UsersService $service
     */
    public function __construct(
        JsonApiServiceInterface $jsonApiService,
        LoggerInterface $logger,
        UsersService $service
    ) {
        parent::__construct($jsonApiService);

        $this->logger = $logger;
        $this->service = $service;
    }

    /**
     * Create a user
     *
     * @param Request $request
     *
     * @Route("/v1/users", methods={"POST"}, name="users.create")
     * @ApiRequest(
     *     query="App\Dto\UserParams",
     *     body="App\Dto\UserDocument",
     *     serialization={"Default", "CreateUser"}, validation={"Default", "CreateUser"}
     * )
     * @return Response
     * @throws Exception
     */
    public function create(Request $request): Response
    {
        dump(666); exit();

        $apiRequest = $this->jsonApiService->parseRequest($request);

        /* @var $user UserDto */
        $user = $apiRequest->getBody()->data;

        // $user = $this->service->create($user);

        return $this->buildContentResponse($apiRequest, $user);
    }
}
