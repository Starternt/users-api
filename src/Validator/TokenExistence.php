<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Validator
 *
 * @Annotation
 */
class TokenExistence extends Constraint
{
    const TOKEN_DOES_NOT_EXIST = 'f0210033-c7f3-4e2f-8879-210db3eb8e68';
    const TOKEN_EXPIRED = 'd3b91117-1f79-4f1d-8a8c-feb962689b9a';

    /**
     * @inheritdoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
