<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Controller\Account;

use Symfony\Component\Security\Http\Attribute\IsGranted;
use ConnectHolland\UserBundle\Form\Account\ProfileType;
use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\UpdateEvent;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultData;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultInterface;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultServiceLocatorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

final class ProfileController
{
    /**
     * @var Environment
     */
    private $twig;

    private array $groups = ['account'];

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher, Environment $twig)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->twig            = $twig;
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route(path: ['en' => '/account/profile', 'nl' => '/account/profiel'], name: 'connectholland_user_account_profile', methods: ['GET', 'POST'], defaults: ['formName' => ProfileType::class])]
    #[Route(path: '/api/account/profile', name: 'connectholland_user_account_profile.api', methods: ['GET', 'POST'], defaults: ['formName' => ProfileType::class])]
    public function edit(ResultServiceLocatorInterface $resultServiceLocator, Request $request, FormInterface $form): ResultInterface
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $event = new UpdateEvent($form->getData());
            $this->eventDispatcher->dispatch($event);
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
                        'user'   => $form->getData(),
                        'errors' => $errors,
                    ],
                    [
                        'template' => '@ConnecthollandUser/forms/account/profile.html.twig',
                        'groups'   => $this->groups,
                    ]
                )
        );
    }
}
