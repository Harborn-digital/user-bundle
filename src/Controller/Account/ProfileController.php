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
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultData;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultInterface;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultServiceLocatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class ProfileController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var array
     */
    private $groups = ['account'];

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
     * @Route("/account/profiel", name="connectholland_user_account_profile", methods={"GET", "POST"}, defaults={"connectholland_user_account_profile_template"="@ConnecthollandUser/forms/account/profile.html.twig"})
     * @Route("/api/account/profile", name="connectholland_user_account_profile.api", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function edit(ResultServiceLocatorInterface $resultServiceLocator, Request $request, FormFactoryInterface $formFactory): ResultInterface
    {
        $template = $request->get('connectholland_user_account_profile_template');
        $form     = $formFactory->create(ProfileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new UpdateEvent($form->getData());
            $this->eventDispatcher->dispatch($event);
        }

        return $resultServiceLocator
            ->getResult(
                $request,
                new ResultData(
                    'profile',
                    [
                        'form' => $form->createView(),
                        'user' => $form->getData(),
                    ],
                    [
                        'template' => $template,
                        'groups'   => $this->groups,
                    ]
                )
        );
    }
}
