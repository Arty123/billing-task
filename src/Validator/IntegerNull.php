<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\IntegerNullConstraint;

class IntegerNull extends Constraint
{
    /**
     * Check that value is integer or null
     *
     * @param mixed $value
     * @param Constraint|IntegerNullConstraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null !== $value || !is_integer($value)) {
            $this->fail($constraint);

            return;
        }
    }

    /**
     * Rise validation error
     *
     * @param Constraint|IntegerNullConstraint $constraint
     */
    private function fail(Constraint $constraint): void
    {
        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
