<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Form;

use ConnectHolland\UserBundle\Security\PasswordConstraints;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as BasePasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @codeCoverageIgnore There is something not working with the RepeatedType config
 */
class NewPasswordType extends AbstractType
{
    public function __construct(private readonly PasswordConstraints $passwordConstraints)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'password',
            RepeatedType::class,
            [
                'type'          => BasePasswordType::class,
                'required'      => true,
                'first_options' => [
                    'label'       => 'connectholland_user.reset.new_password.password',
                    'constraints' => $this->passwordConstraints->getConstraints(),
                ],
                'second_options' => [
                    'label' => 'connectholland_user.reset.new_password.password_repeat',
                ],
                'invalid_message'    => 'connectholland_user.validation.reset.new_password.password.repeat_invalid',
                'translation_domain' => 'ConnecthollandUserBundle',
            ]
        );
    }

    /**
     * @codeCoverageIgnore No need to test setting options for forms without functionality.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
