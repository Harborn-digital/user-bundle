<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Controller\Account;

use ConnectHolland\UserBundle\Form\Account\ProfileType;
use Symfony\Component\Form\FormError;
use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\UpdateEvent;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultData;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultInterface;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultServiceLocatorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

final class ProfileController
{
    /**
     * @var array
     */
    private $groups = ['account'];

    public function __construct(private readonly EventDispatcherInterface $eventDispatcher, private readonly Environment $twig)
    {
    }

    #[Route(path: ['en' => '/account/profile', 'nl' => '/account/profiel'], name: 'connectholland_user_account_profile', defaults: ['formName' => ProfileType::class], methods: ['GET', 'POST'])]
    #[Route(path: '/api/account/profile', name: 'connectholland_user_account_profile.api', defaults: ['formName' => ProfileType::class], methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(ResultServiceLocatorInterface $resultServiceLocator, Request $request, FormInterface $form): ResultInterface
    {
        if ($form->isSubmitted() && $form->isValid()) {
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
                        'template' => '@ConnecthollandUser/forms/account/profile.html.twig',
                        'groups'   => $this->groups,
                    ]
                )
        );
    }
}
