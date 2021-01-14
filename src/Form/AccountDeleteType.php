<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Form;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\IsTrue;

class AccountDeleteType extends AbstractType
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var TokenStorageInterface|null
     */
    private $tokenStorage;

    public function __construct(ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage)
    {
        $this->doctrine     = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('delete', CheckboxType::class, [
                'label'              => 'connectholland_user.account.delete_account_check',
                'translation_domain' => 'ConnecthollandUserBundle',
                'mapped'             => false,
                'constraints'        => [
                    new IsTrue(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $user = $this->getUser();
        $resolver->setDefaults([
            'data_class' => $this->doctrine->getRepository(UserInterface::class)->getClassName(),
            'data'       => $user,
            'attr'       => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }

    private function getUser(): ?UserInterface
    {
        if ($this->tokenStorage !== null && $this->tokenStorage->getToken() !== null) {
            $user = $this->tokenStorage->getToken()->getUser();
            if ($user instanceof UserInterface) {
                return $user;
            }
        }

        return null;
    }
}
