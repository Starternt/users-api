<?php

namespace App\Dto;

use Reva2\JsonApi\Annotations\ApiDocument;
use Reva2\JsonApi\Annotations\Content;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * JSON API document that contains session
 *
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Dto
 *
 * @ApiDocument()
 */
class SessionDocument
{
    /**
     * @var SessionDto
     * @Content(type="App\Dto\SessionDto")
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $data;
}
