<?php

namespace App\Dto;

use Reva2\JsonApi\Annotations\Property;
use Reva2\JsonApi\Http\Query\QueryParameters;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\TokenExistence;

/**
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Dto
 * @TokenExistence()
 */
class ActivationLinkParams extends QueryParameters
{
    /**
     * @var string
     * @Property(path="[token]")
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     */
    protected $token;

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
     * @return ActivationLinkParams
     */
    public function setToken(string $token): ActivationLinkParams
    {
        $this->token = $token;

        return $this;
    }
}
