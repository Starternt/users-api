<?php

namespace App\Controller;

use App\Dto\ActivationLinkParams;
use App\Dto\UserDto;
use App\Service\UsersService;
use App\Utils\JsonApi\JsonApiErrorsTrait;
use Exception;
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
     * @var UsersService
     */
    protected $service;

    /**
     * @param JsonApiServiceInterface $jsonApiService
     * @param UsersService $service
     */
    public function __construct(
        JsonApiServiceInterface $jsonApiService,
        UsersService $service
    ) {
        parent::__construct($jsonApiService);

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
        $apiRequest = $this->jsonApiService->parseRequest($request);

        /* @var $user UserDto */
        $user = $apiRequest->getBody()->data;

        $user = $this->service->create($user);

        return $this->buildContentResponse($apiRequest, $user);
    }

    /**
     * User activation
     *
     * @param Request $request
     *
     * @Route("/v1/users/activate", methods={"POST"}, name="users.activate")
     * @ApiRequest(
     *     query="App\Dto\ActivationLinkParams"
     * )
     * @return Response
     * @throws Exception
     */
    public function activate(Request $request): Response
    {
        $apiRequest = $this->jsonApiService->parseRequest($request);

        /** @var ActivationLinkParams $query */
        $query = $apiRequest->getQuery();
        $this->service->activateUser($query);

        return $this->buildEmptyResponse($apiRequest, Response::HTTP_ACCEPTED);
    }
}
