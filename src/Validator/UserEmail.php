<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Validator
 *
 * @Annotation
 */
class UserEmail extends Constraint
{
    const EMAIL_NOT_UNIQUE = 'a16399c4-2946-405b-a860-4d4a3bc3b684';

    /**
     * @inheritdoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
