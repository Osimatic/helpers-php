<?php

namespace Osimatic\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;

class CompanyNameValidator extends ConstraintValidator
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
			return ;
		}

		/** @var CompanyName $constraint */
		if (!\Osimatic\Organization\Company::checkCompanyName($value)) {
			$this->context->buildViolation($constraint->message)
				->setParameter('{{value}}', $value)
				->addViolation();
		}
	}
}