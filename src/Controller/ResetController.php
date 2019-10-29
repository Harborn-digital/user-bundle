<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Controller;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\ResetUserEvent;
use ConnectHolland\UserBundle\Event\UserResetEvent;
use ConnectHolland\UserBundle\Form\NewPasswordType;
use ConnectHolland\UserBundle\Form\ResetType;
use ConnectHolland\UserBundle\Security\UserBundleAuthenticator;
use ConnectHolland\UserBundle\UserBundleEvents;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Twig\Environment;

/**
 * @codeCoverageIgnore WIP
 */
final class ResetController
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(RegistryInterface $registry, Session $session, EventDispatcherInterface $eventDispatcher, RouterInterface $router, Environment $twig)
    {
        $this->registry        = $registry;
        $this->session         = $session;
        $this->eventDispatcher = $eventDispatcher;
        $this->router          = $router;
        $this->twig            = $twig;
    }

    /**
     * @Route("/wachtwoord-vergeten", name="connectholland_user_reset", methods={"GET", "POST"})
     */
    public function reset(Request $request, FormFactoryInterface $formFactory): Response
    {
        $form = $formFactory->create(ResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ResetUserEvent $event */
            /** @scrutinizer ignore-call */
            $event = $this->eventDispatcher->dispatch(UserBundleEvents::RESET_USER, new ResetUserEvent($form->get('email')->getData()));
            if (/* @scrutinizer ignore-deprecated */ $event->isPropagationStopped() === false) {
                /** @var UserReset $event */
                /** @scrutinizer ignore-call */
                $event = $this->eventDispatcher->dispatch(UserBundleEvents::USER_RESET, new UserResetEvent($event->getEmail()));
                if (/* @scrutinizer ignore-deprecated */ $event->isPropagationStopped() === false) {
                    $this->session->getFlashBag()->add('notice', 'Check your e-mail to complete your password reset');

                    $form = $formFactory->create(ResetType::class); // reset input
                }
            }
        }

        return new Response(
            $this->twig->render(
                '@ConnecthollandUser/forms/reset.html.twig',
                [
                    'form' => $form->createView(),
                ]
            )
        );
    }

    /**
     * @Route("/wachtwoord-vergeten/{email}/{token}", name="connectholland_user_reset_confirm", methods={"GET", "POST"})
     */
    public function resetPassword(
        Request $request,
        string $email,
        string $token,
        FormFactoryInterface $formFactory,
        UserPasswordEncoderInterface $encoder,
        UriSigner $uriSigner,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        UserBundleAuthenticator $authenticator
    ): Response {
        /** @var EntityManagerInterface $userManager */
        $userManager = $this->registry->getManagerForClass(User::class);

        $user = $this->registry->getRepository(User::class)->findOneBy(['passwordRequestToken' => $token, 'email' => $email]);
        if ($user instanceof UserInterface && $uriSigner->check(sprintf('%s://%s%s', $request->getScheme(), $request->getHttpHost(), $request->getRequestUri())) === false) {
            $user->setPasswordRequestToken(null);

            $userManager->persist($user);
            $userManager->flush();

            $user = null;
        }

        if ($user instanceof UserInterface === false) {
            $this->session->getFlashBag()->add('danger', 'User not found');

            return new RedirectResponse($this->router->generate('connectholland_user_reset'));
        }

        $form = $formFactory->create(NewPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $password      = $encoder->encodePassword($user, $plainPassword);

            $user->setPassword($password);
            $user->setPasswordRequestToken(null);
            $user->setEnabled(true);

            $userManager->persist($user);
            $userManager->flush();

            return $this->authenticateUser($request, $user, $guardAuthenticatorHandler, $authenticator);
        }

        return new Response(
            $this->twig->render(
                '@ConnecthollandUser/forms/new_password.html.twig',
                [
                    'form' => $form->createView(),
                ]
            )
        );
    }

    /**
     * Login a User manually.
     */
    private function authenticateUser(Request $request, User $user, GuardAuthenticatorHandler $guardAuthenticatorHandler, UserBundleAuthenticator $authenticator): ?Response
    {
        $providerKey = 'main'; // TODO: Make configurable

        $token = $authenticator->createAuthenticatedToken($user, $providerKey);

        $guardAuthenticatorHandler->authenticateWithToken($token, $request, $providerKey);

        return $guardAuthenticatorHandler->handleAuthenticationSuccess($token, $request, $authenticator, $providerKey);
    }
}
