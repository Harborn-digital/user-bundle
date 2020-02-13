<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Controller\Account;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\UpdateEvent;
use ConnectHolland\UserBundle\Form\Account\ProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @codeCoverageIgnore WIP
 * @Route({"en": "/account", "nl": "/account"}, name="connectholland_user_account")
 */
final class ProfileController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher, Environment $twig)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->twig            = $twig;
    }

    /**
     * @Route("/profiel", name="_profile", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function edit(Request $request, FormFactoryInterface $formFactory): Response
    {
        $form = $formFactory->create(ProfileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new UpdateEvent($form->getData());
            $this->eventDispatcher->dispatch($event);
        }

        return new Response(
            $this->twig->render(
                '@ConnecthollandUser/forms/account/profile.html.twig',
                [
                    'form' => $form->createView(),
                ]
            )
        );
    }
}
