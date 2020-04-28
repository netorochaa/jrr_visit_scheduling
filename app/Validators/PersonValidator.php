<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class PersonValidator.
 *
 * @package namespace App\Validators;
 */
class PersonValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => 'required',
            'fone' => 'required',
            'CPF' => 'required|unique:people,CPF',
            'birth' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
