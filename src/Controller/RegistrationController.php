<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Controller;

use ConnectHolland\UserBundle\Form\RegistrationType;
use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\AuthenticateUserEvent;
use ConnectHolland\UserBundle\Event\CreateUserEvent;
use ConnectHolland\UserBundle\Event\PostRegistrationEvent;
use ConnectHolland\UserBundle\Event\UserCreatedEvent;
use ConnectHolland\UserBundle\Event\UserNotFoundEvent;
use ConnectHolland\UserBundle\Repository\UserRepository;
use ConnectHolland\UserBundle\UserBundleEvents;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultData;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultInterface;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultServiceLocatorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @codeCoverageIgnore WIP
 */
final class RegistrationController
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $eventDispatcher, RouterInterface $router)
    {
        $this->registry        = $registry;
        $this->eventDispatcher = $eventDispatcher;
        $this->router          = $router;
    }

    /**
     *
     * @param FormInterface<mixed> $form
     */
    #[Route(path: ['en' => '/register', 'nl' => '/registreren'], name: 'connectholland_user_registration', methods: ['GET', 'POST'], defaults: ['formName' => RegistrationType::class])]
    #[Route(path: '/api/register', name: 'connectholland_user_registration.api', methods: ['GET', 'POST'], defaults: ['formName' => RegistrationType::class])]
    public function register(ResultServiceLocatorInterface $resultServiceLocator, Request $request, FormInterface $form): ResultInterface
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $createUserEvent = new CreateUserEvent($form->getData(), $form->get('plainPassword')->getData());
            $this->eventDispatcher->dispatch($createUserEvent, UserBundleEvents::CREATE_USER);
            if ($createUserEvent->isPropagationStopped() === false) {
                $userCreatedEvent = new UserCreatedEvent($createUserEvent->getUser());
                $this->eventDispatcher->dispatch($userCreatedEvent, UserBundleEvents::USER_CREATED);
                if ($userCreatedEvent->isPropagationStopped() === false) {
                    $defaultResponse       = new RedirectResponse($this->router->generate($request->attributes->get('_route')));
                    $postRegistrationEvent = new PostRegistrationEvent('success', $defaultResponse, __FUNCTION__);
                    $this->eventDispatcher->dispatch($postRegistrationEvent, UserBundleEvents::REGISTRATION_COMPLETED);

                    return $postRegistrationEvent;
                }
            }
        }

        $errors = [];
        /** @var \Symfony\Component\Form\FormError $error */
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
                        'errors' => $errors,
                    ],
                    [
                        'template' => '@ConnecthollandUser/forms/register.html.twig',
                    ]
                )
        );
    }

    #[Route(path: ['en' => '/register/confirm/{email}/{token}', 'nl' => '/registreren/bevestigen/{email}/{token}'], name: 'connectholland_user_registration_confirm', methods: ['GET', 'POST'])]
    #[Route(path: '/api/register/confirm/{email}/{token}', name: 'connectholland_user_registration_confirm.api', methods: ['GET', 'POST'])]
    public function registrationConfirm(Request $request, string $email, string $token, UriSigner $uriSigner): Response
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->registry->getRepository(UserInterface::class);

        /** @var User|null $user */
        $user = $userRepository->findOneBy(['email' => $email, 'passwordRequestToken' => $token]);

        if (!($user instanceof UserInterface) || $uriSigner->check(sprintf('%s://%s%s', $request->getScheme(), $request->getHttpHost(), $request->getRequestUri())) === false) {
            $defaultResponse   = new RedirectResponse($this->router->generate('connectholland_user_registration'));
            $userNotFoundEvent = new UserNotFoundEvent($defaultResponse, 'danger', __FUNCTION__);
            $this->eventDispatcher->dispatch($userNotFoundEvent, UserBundleEvents::USER_NOT_FOUND);

            return $userNotFoundEvent->getResponse();
        }

        $user->setEnabled(true);
        $user->setPasswordRequestToken(null);

        /** @var EntityManagerInterface $userManager */
        $userManager = $this->registry->getManagerForClass(User::class);
        $userManager->flush();

        $authenticateUserEvent = new AuthenticateUserEvent($user, $request);
        $this->eventDispatcher->dispatch($authenticateUserEvent, UserBundleEvents::AUTHENTICATE_USER);
        if (null !== $authenticateUserEvent->getResponse()) {
            return $authenticateUserEvent->getResponse();
        }

        return new RedirectResponse('/'); // TODO: use a correct redirect route/path to login
    }
}
