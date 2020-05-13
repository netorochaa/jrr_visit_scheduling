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
            'name' => 'required',
            'displacementRate' => 'required',
            'city_id' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
        ],
    ];
}
