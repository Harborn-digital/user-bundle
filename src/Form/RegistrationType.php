<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Form;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

/**
 * @codeCoverageIgnore WIP
 */
final class RegistrationType extends AbstractType
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'              => 'connectholland_user.registration.email',
                'translation_domain' => 'ConnecthollandUserBundle',
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label'              => 'connectholland_user.registration.plain_password',
                'mapped'             => false,
                'translation_domain' => 'ConnecthollandUserBundle',
            ])
            ->add('terms', CheckboxType::class, [
                'label'              => 'connectholland_user.registration.terms',
                'mapped'             => false,
                'translation_domain' => 'ConnecthollandUserBundle',
                'constraints'        => [
                    new IsTrue([
                        'message' => 'connectholland_user.validation.registration.terms.is_true',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->doctrine->getRepository(UserInterface::class)->getClassName(),
            'attr'       => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
