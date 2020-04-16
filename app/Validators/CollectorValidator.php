<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class CollectorValidator.
 *
 * @package namespace App\Validators;
 */
class CollectorValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => 'required|unique:collectors',            
            'user_id' => 'required|unique:collectors'
        ],
        ValidatorInterface::RULE_UPDATE => [
        ],
    ];
}
