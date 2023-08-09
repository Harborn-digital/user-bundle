<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\ArgumentResolver;

use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Generator;
use ReflectionClass;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Resolves a form as argument for a controller action
 * when the FORM_NAME_ATTRIBUTE is set in the routing.
 */
class FormValueResolver implements ValueResolverInterface
{
    /**
     * Defines the key used to look up the form name in the request attributes.
     *
     * @var string
     */
    final public const FORM_NAME_ATTRIBUTE = 'formName';

    /**
     * The factory needed to create a new form when it should be created as controller action argument.
     *
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * Create a new FormValueResolver.
     *
     * @param formFactoryInterface $formFactory - The factory used to create a new form when defined in the route
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Return true when the FormValueResolver supports handling the given argument for the given request.
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (FormInterface::class !== $argument->getType()) {
            return false;
        }

        if ($request->attributes->has(self::FORM_NAME_ATTRIBUTE) === false) {
            return false;
        }

        $reflect = new ReflectionClass($request->attributes->get(self::FORM_NAME_ATTRIBUTE));

        return $reflect->implementsInterface(FormTypeInterface::class);
    }

    /**
     * Resolve the form as argument and let the form handle the request.
     *
     * @return Generator<FormInterface>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        if (!$this->supports($request, $argument)) {
            return [];
        }

        $form = $this->getForm($request);
        if ($form instanceof FormInterface) {
            $this->handleRequest($request, $form);
        }

        yield $form;
    }

    /**
     * Return the form for the given request.
     *
     * @return FormInterface<mixed> $form
     */
    private function getForm(Request $request): ?FormInterface
    {
        $form     = null;
        $formName = (string) $request->attributes->get(self::FORM_NAME_ATTRIBUTE);
        if (class_exists($formName)) {
            $form = $this->formFactory->create($formName, null, ['allow_extra_fields' => true, 'csrf_protection' => false]);
        }

        return $form;
    }

    /**
     * Let the given form handle the given request data.
     * Do not clear missing fields, to allow clients to only send fields that need updating.
     *
     * @param FormInterface<mixed> $form
     */
    private function handleRequest(Request $request, FormInterface $form): void
    {
        try {
            $content = json_decode((string) $request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            $content = null;
        }

        if ($content) {
            $form->submit($content, false); // do not clear missing fields

            return;
        }

        $form->handleRequest($request);
    }
}
