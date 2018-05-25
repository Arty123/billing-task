<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Validator\IntegerNull;
use Symfony\Component\Validator\Constraint;

class IntegerNullConstraint extends Constraint
{
    public $message = 'Field is not integer or null';

    public function validatedBy(): string
    {
        return IntegerNull::class;
    }
}
