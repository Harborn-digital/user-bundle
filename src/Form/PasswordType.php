<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Form;

use Rollerworks\Component\PasswordStrength\Validator\Constraints\P0wnedPassword;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as BasePasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @codeCoverageIgnore Contains no functionality as there is no buildForm, only configure methods are used.
 */
class PasswordType extends AbstractType
{
    /**
     * @var array
     */
    private $passwordRequirements;

    public function __construct(array $passwordRequirements)
    {
        $this->passwordRequirements = $passwordRequirements;
    }

    public function getParent(): string
    {
        return BasePasswordType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $constrainsts = [
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
            $constrainsts[] = new P0wnedPassword();
        }

        $resolver->setDefaults([
            'constraints' => $constrainsts,
        ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
