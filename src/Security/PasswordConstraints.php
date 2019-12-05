<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Security;

use Rollerworks\Component\PasswordStrength\Validator\Constraints\P0wnedPassword;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @codeCoverageIgnore Just build up an array of constraints.
 */
class PasswordConstraints
{
    /**
     * @var array
     */
    private $passwordRequirements;

    public function __construct(array $passwordRequirements)
    {
        $this->passwordRequirements = $passwordRequirements;
    }

    public function getConstraints(): array
    {
        $constraints = [
            new NotBlank([
                'message' => 'connectholland_user.validation.password.not_blank',
            ]),
            new Length([
                // max length allowed by Symfony for security reasons
                'max' => 4096,
            ]),
            new PasswordStrength([
                'tooShortMessage' => 'connectholland_user.validation.password.password_strength.too_short',
                'message'         => 'connectholland_user.validation.password.password_strength.too_weak',
                'minLength'       => $this->passwordRequirements['min_length'],
                'minStrength'     => $this->passwordRequirements['min_strength'],
            ]),
        ];

        if ($this->passwordRequirements['not_pwned'] === true) {
            $constraints[] = new P0wnedPassword([
                'message' => 'connectholland_user.validation.password.p0wned_password',
            ]);
        }

        return $constraints;
    }
}
