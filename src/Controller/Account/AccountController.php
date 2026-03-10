<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Controller\Account;

use ConnectHolland\UserBundle\Form\Account\AccountType;
use ConnectHolland\UserBundle\Form\AccountDeleteType;
use Symfony\Component\Form\FormError;
use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\DeleteAccountEvent;
use ConnectHolland\UserBundle\Event\UpdateEvent;
use ConnectHolland\UserBundle\Event\UsernameUpdatedEvent;
use Doctrine\Persistence\ManagerRegistry;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultData;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultInterface;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultServiceLocatorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

/**
 * @codeCoverageIgnore WIP
 */
final class AccountController
{
    /**
     * @var array
     */
    private $groups = ['account'];

    public function __construct(private readonly UserPasswordHasherInterface $encoder, private readonly EventDispatcherInterface $eventDispatcher, private readonly Environment $twig, private readonly ManagerRegistry $registry, private readonly TokenStorageInterface $tokenStorage)
    {
    }

    #[Route(path: ['en' => '/account/details', 'nl' => '/account/gegevens'], name: 'connectholland_user_account_account', defaults: ['formName' => AccountType::class], methods: ['GET', 'POST'])]
    #[Route(path: '/api/account/details', name: 'connectholland_user_account_account.api', defaults: ['formName' => AccountType::class], methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(ResultServiceLocatorInterface $resultServiceLocator, UserInterface $user, Request $request, FormInterface $form): ResultInterface
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \ConnectHolland\UserBundle\Entity\UserInterface $user */
            $email         = $user->getEmail();
            $plainPassword = $form->get('plainPassword')->getData();

            if ($email !== $user->getEmail()) {
                $user->setPasswordRequestToken(bin2hex(random_bytes(32)));
                $user->setEnabled(false);
                $event = new UsernameUpdatedEvent($user);
                $event->setArgument('request', $request);
                $this->eventDispatcher->dispatch($event);
            }

            if (!empty($plainPassword)) {
                $password = $this->encoder->hashPassword($user, $plainPassword);
                $user->setPassword($password);
            }

            $event = new UpdateEvent($form->getData());
            $this->eventDispatcher->dispatch($event);
        }

        $errors = [];
        /** @var FormError $error */
        foreach ($form->getErrors(true, true) as $error) {
            $errors[$error->getMessageTemplate()] = $error->getMessage();
        }

        return $resultServiceLocator
            ->getResult(
                $request,
                new ResultData(
                    'profile',
                    [
                        'form'   => $form->createView(),
                        'user'   => $form->getData(),
                        'errors' => $errors,
                    ],
                    [
                        'template' => '@ConnecthollandUser/forms/account/account.html.twig',
                        'groups'   => $this->groups,
                    ]
                )
        );
    }

    #[Route(path: ['en' => '/account/delete', 'nl' => '/account/verwijderen'], name: 'connectholland_user_account_delete', defaults: ['formName' => AccountDeleteType::class], methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(UserInterface $user, Request $request, FormInterface $form): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->registry->getManagerForClass(User::class)->remove($user);
            $this->registry->getManagerForClass(User::class)->flush();

            $this->tokenStorage->setToken(null);
            $request->getSession()->invalidate();

            $event = new DeleteAccountEvent($form->getData());
            $this->eventDispatcher->dispatch($event);

            return $event->getResponse();
        }

        $errors = [];
        /** @var FormError $error */
        foreach ($form->getErrors(true, true) as $error) {
            $errors[$error->getMessageTemplate()] = $error->getMessage();
        }

        return new Response(
            $this->twig->render('@ConnecthollandUser/forms/account/delete.html.twig',
                [
                    'form'   => $form->createView(),
                    'errors' => $errors,
                ]
            )
        );
    }

    public function setGroups(array $groups): void
    {
        $this->groups = $groups;
    }
}
