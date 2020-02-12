<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Controller\Account;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\UpdateEvent;
use ConnectHolland\UserBundle\Event\UsernameUpdatedEvent;
use ConnectHolland\UserBundle\Form\Account\AccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

/**
 * @codeCoverageIgnore WIP
 * @Route({"en": "/account", "nl": "/account"}, name="connectholland_user_account")
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

    public function __construct(UserPasswordEncoderInterface $encoder, EventDispatcherInterface $eventDispatcher, Environment $twig)
    {
        $this->encoder         = $encoder;
        $this->eventDispatcher = $eventDispatcher;
        $this->twig            = $twig;
    }

    /**
     * @Route({"en": "/details", "nl": "/gegevens"}, name="_account", methods={"GET", "POST"})
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
}
