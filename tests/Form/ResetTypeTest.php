<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Form;

use ConnectHolland\UserBundle\Form\ResetType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Form\ResetType
 */
class ResetTypeTest extends TypeTestCase
{
    protected function getExtensions(): ?array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator)
        ];
    }

    /**
     * @covers ::buildForm
     */
    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'example@example.com',
        ];

        $objectToCompare        = new \stdClass();
        $objectToCompare->email = null;
        $object                 = new \stdClass();
        $object->email          = 'example@example.com';

        $form = $this->factory->create(ResetType::class, $objectToCompare);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $objectToCompare);

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $form->createView()->children);
        }

        $this->assertTrue($form->get('email')->getConfig()->getOption('required'));
        $this->assertInstanceOf(EmailType::class, $form->get('email')->getConfig()->getType()->getInnerType());
    }
}
