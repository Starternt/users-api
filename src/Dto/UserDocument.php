<?php

namespace App\Dto;

use Reva2\JsonApi\Annotations\ApiDocument;
use Reva2\JsonApi\Annotations\Content;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * JSON API document that contains single user
 *
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Dto
 *
 * @ApiDocument()
 */
class UserDocument
{
    /**
     * @var UserDto
     * @Content(type="App\Dto\UserDto")
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    public $data;
}
