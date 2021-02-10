<?php

namespace App\Dto;

use Reva2\JsonApi\Http\Query\QueryParameters;

/**
 * Query parameters for endpoint that returns information about
 * specified user
 *
 * @author Konstantin Laktionov <Starternh@gmail.com>
 * @package App\Dto
 */
class UserParams extends QueryParameters
{
    /**
     * @inheritdoc
     */
    protected function getAllowedIncludePaths()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getAllowedFields($resource)
    {
        switch ($resource) {
            case 'users':
                return [
                    'id',
                    'login',
                    'email'
                ];

            default:
                return parent::getFieldSets();
        }
    }
}
