<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Validator
 *
 * @Annotation
 */
class UserLogin extends Constraint
{
    const LOGIN_NOT_UNIQUE = 'f40eba74-1654-47df-a1a7-da0f5224753b';

    /**
     * @inheritdoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
