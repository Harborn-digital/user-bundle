<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as BasePasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @codeCoverageIgnore WIP
 */
final class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            '_username',
            TextType::class,
            [
                'label'              => 'connectholland_user.security.login.username',
                'required'           => true,
                'translation_domain' => 'ConnecthollandUserBundle',
            ]
        );
        $builder->add(
            '_password',
            BasePasswordType::class,
            [
                'label'              => 'connectholland_user.security.login.password',
                'required'           => true,
                'translation_domain' => 'ConnecthollandUserBundle',
            ]
        );
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr'       => [
                'novalidate' => 'novalidate',
            ],
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'authenticate',
        ]);
    }
}
