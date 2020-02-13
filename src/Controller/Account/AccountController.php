<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Controller\Account;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\DeleteAccountEvent;
use ConnectHolland\UserBundle\Event\UpdateEvent;
use ConnectHolland\UserBundle\Event\UsernameUpdatedEvent;
use ConnectHolland\UserBundle\Form\Account\AccountType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

/**
 * @codeCoverageIgnore WIP
 * @Route("/account", name="connectholland_user_account")
 */
final class AccountController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(UserPasswordEncoderInterface $encoder, EventDispatcherInterface $eventDispatcher, Environment $twig, ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        $this->encoder         = $encoder;
        $this->eventDispatcher = $eventDispatcher;
        $this->twig            = $twig;
        $this->registry        = $registry;
        $this->tokenStorage    = $tokenStorage;
    }

    /**
     * @Route("/gegevens", name="_account", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function edit(UserInterface $user, Request $request, FormFactoryInterface $formFactory): Response
    {
        /** @var \ConnectHolland\UserBundle\Entity\UserInterface $user */
        $email = $user->getEmail();
        $form  = $formFactory->create(AccountType::class);
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();

            if ($email !== $user->getEmail()) {
                $user->setPasswordRequestToken(bin2hex(random_bytes(32)));
                $user->setEnabled(false);
                $event = new UsernameUpdatedEvent($user);
                $event->setArgument('request', $request);
                $this->eventDispatcher->dispatch($event);
            }

            if (!empty($plainPassword)) {
                $password = $this->encoder->encodePassword($user, $plainPassword);
                $user->setPassword($password);
            }

            $event = new UpdateEvent($form->getData());
            $this->eventDispatcher->dispatch($event);
        }

        return new Response(
            $this->twig->render(
                '@ConnecthollandUser/forms/account/account.html.twig',
                [
                    'form' => $form->createView(),
                ]
            )
        );
    }

    /**
     * @Route("/verwijderen", name="_delete", methods={"GET", "POST"}, defaults={"formName"="ConnectHolland\UserBundle\Form\AccountDeleteType"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
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

        return new Response(
            $this->twig->render('@ConnecthollandUser/forms/account/delete.html.twig',
                [
                    'form' => $form->createView(),
                ]
            )
        );
    }
}
