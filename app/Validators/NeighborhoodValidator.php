<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class NeighborhoodValidator.
 *
 * @package namespace App\Validators;
 */
class NeighborhoodValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => 'required|unique:neighborhood,name',
            'displacementRate' => 'required',
            'city_id' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
