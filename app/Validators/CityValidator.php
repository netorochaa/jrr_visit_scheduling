<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class CityValidator.
 *
 * @package namespace App\Validators;
 */
class CityValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => 'required|unique:cities,name',
            'UF'   => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
