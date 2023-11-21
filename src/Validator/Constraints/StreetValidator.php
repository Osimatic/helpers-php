<?php

namespace Osimatic\Helpers\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StreetValidator extends ConstraintValidator
{
	/**
	 * Checks if the passed value is valid.
	 *
	 * @param mixed $value The value that should be validated
	 * @param Constraint $constraint The constraint for the validation
	 */
	public function validate(mixed $value, Constraint $constraint): void
	{
		if (null === $value || '' === $value) {
			return;
		}

		/** @var Street $constraint */
		if (!\Osimatic\Helpers\Location\PostalAddress::checkStreet($value)) {
			$this->context->buildViolation($constraint->message)
				->setParameter('{{string}}', $value)
				->addViolation();
		}
	}
}