<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class FreeDayValidator.
 *
 * @package namespace App\Validators;
 */
class FreeDayValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => 'required', 
            'dateRange' => 'required'            
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
