<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Controller;

use ConnectHolland\UserBundle\Form\LoginType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class SecurityController
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(AuthenticationUtils $authenticationUtils, FormFactoryInterface $formFactory, Environment $twig)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->formFactory         = $formFactory;
        $this->twig                = $twig;
    }

    /**
     * @Route("/inloggen", name="connectholland_user_login", methods={"GET", "POST"})
     */
    public function login(): Response
    {
        $form         = $this->formFactory->create(LoginType::class);
        $lastUsername = $this->authenticationUtils->getLastUsername();
        $error        = $this->authenticationUtils->getLastAuthenticationError();

        return new Response(
            $this->twig->render(
                '@ConnecthollandUser/security/login.html.twig',
                [
                    'form'          => $form->createView(),
                    'last_username' => $lastUsername,
                    'error'         => $error,
                ]
            )
        );
    }
}
