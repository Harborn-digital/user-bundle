<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Form\Account;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @codeCoverageIgnore Contains no functionality as there is no buildForm, only configure methods are used.
 */
class ProfileType extends AbstractType
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => $this->doctrine->getRepository(UserInterface::class)->getClassName(),
            'translation_domain' => 'ConnecthollandUserBundle',
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
