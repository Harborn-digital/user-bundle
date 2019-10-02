<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Controller;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\CreateUserEvent;
use ConnectHolland\UserBundle\Event\UserCreatedEvent;
use ConnectHolland\UserBundle\Form\RegistrationType;
use ConnectHolland\UserBundle\Repository\UserRepository;
use ConnectHolland\UserBundle\UserBundleEvents;
use Doctrine\ORM\EntityManagerInterface;
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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Twig\Environment;

class RegistrationController
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(RegistryInterface $registry, Session $session, EventDispatcherInterface $eventDispatcher, TokenStorageInterface $tokenStorage, RouterInterface $router, Environment $twig)
    {
        $this->registry        = $registry;
        $this->session         = $session;
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenStorage    = $tokenStorage;
        $this->router          = $router;
        $this->twig            = $twig;
    }

    /**
     * @Route("/registreren", name="connectholland_user_registration", methods={"GET", "POST"})
     */
    public function register(Request $request, FormFactoryInterface $formFactory): Response
    {
        $form = $formFactory->create(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CreateUserEvent $event */
            $event = $this->eventDispatcher->dispatch(UserBundleEvents::CREATE_USER, new CreateUserEvent($form->getData(), $form->get('plainPassword')->getData()));
            if ($event->isPropagationStopped() === false) {
                /** @var UserCreatedEvent $event */
                $event = $this->eventDispatcher->dispatch(UserBundleEvents::USER_CREATED, new UserCreatedEvent($event->getUser()));
                if ($event->isPropagationStopped() === false) {
                    $this->session->getFlashBag()->add('notice', 'Check your e-mail to complete your registration');

                    return new RedirectResponse('/'); // TODO: use a correct redirect route/path to login
                }
            }
        }

        return new Response(
            $this->twig->render(
                '@ConnecthollandUser/registration/register.html.twig',
                [
                    'form' => $form->createView(),
                ]
            )
        );
    }

    /**
     * @Route("/registreren/bevestigen/{email}/{token}", name="connectholland_user_registration_confirm", methods={"GET", "POST"})
     */
    public function registrationConfirm(Request $request, string $email, string $token, UriSigner $uriSigner): Response
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->registry->getRepository(User::class);

        /** @var User $user */
        $user = $userRepository->findOneBy(['email' => $email, 'passwordRequestToken' => $token]);

        if (!($user instanceof UserInterface) || $uriSigner->check(sprintf('%s://%s%s', $request->getScheme(), $request->getHttpHost(), $request->getRequestUri())) === false) {
            $this->session->getFlashBag()->add('danger', 'User was not found');

            return new RedirectResponse('/'); // TODO: use a correct redirect route/path to login
        }

        $user->setEnabled(true);
        $user->setPasswordRequestToken(null);

        /** @var EntityManagerInterface $userManager */
        $userManager = $this->registry->getManagerForClass(User::class);
        $userManager->flush();

        $this->authenticateUser($request, $user);

        return new RedirectResponse('/'); // TODO: use a correct redirect route/path
    }

    /**
     * Login a User manually.
     */
    private function authenticateUser(Request $request, User $user): void
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
        $this->session->set('_security_main', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        $this->eventDispatcher->dispatch('security.interactive_login', $event);
    }
}
