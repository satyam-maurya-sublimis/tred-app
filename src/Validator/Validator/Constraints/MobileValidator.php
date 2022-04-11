<?php

namespace App\Validator\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MobileValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Validator\Constraints\Mobile */
        $errors = array();
        if (null === $value || '' === $value) {
            return;
        }
        if (strlen($value) != 10 ) {
            return "Mobile number should be aleast of 10 digit long. ";
        }
        if (!preg_match("/\d{10}/", $value, $matches)) {
            return "Mobile number should be aleast of 10 digit long. ";
        }
        if ($errors) {
            $errorMessages = '';
            foreach ($errors as $error) {
                $errorMessages .= $error;
            }
            // the argument must be a string or an object implementing __toString()
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $errorMessages)
                ->addViolation();
        }
    }
}
