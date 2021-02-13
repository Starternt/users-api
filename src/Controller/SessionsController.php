<?php

namespace App\Controller;

use App\Service\SessionService;
use App\Utils\JsonApi\JsonApiErrorsTrait;
use Exception;
use Psr\Log\LoggerInterface;
use Reva2\JsonApi\Annotations\ApiRequest;
use Reva2\JsonApi\Contracts\Services\JsonApiServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller that implements sessions API
 *
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Controller
 */
class SessionsController extends JsonApiController
{
    use JsonApiErrorsTrait;

    /**
     * @var SessionService
     */
    protected $service;

    /**
     * @param JsonApiServiceInterface $jsonApiService
     * @param LoggerInterface $logger
     * @param SessionService $service
     */
    public function __construct(
        JsonApiServiceInterface $jsonApiService,
        SessionService $service
    ) {
        parent::__construct($jsonApiService);

        $this->service = $service;
    }

    /**
     * Create a session
     *
     * @param Request $request
     *
     * @Route("/v1/sessions", methods={"POST"}, name="sessions.create")
     * @ApiRequest(
     *     body="App\Dto\SessionDocument",
     *     serialization={"Default", "CreateSession"}, validation={"Default", "CreateSession"}
     * )
     * @return Response
     * @throws Exception
     */
    public function login(Request $request): Response
    {
        $apiRequest = $this->jsonApiService->parseRequest($request);

        $sessionDto = $apiRequest->getBody()->data;
        $sessionDto = $this->service->createSession($sessionDto);

        return $this->buildContentResponse($apiRequest, $sessionDto);
    }
}
