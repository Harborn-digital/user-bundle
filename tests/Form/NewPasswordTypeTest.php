<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Form;

use ConnectHolland\UserBundle\Form\NewPasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Form\NewPasswordType
 */
class NewPasswordTypeTest extends TypeTestCase
{
    /**
     * @covers ::buildForm
     */
    public function testSubmitValidData()
    {
        $this->markTestIncomplete(
            'There is something not working with the RepeatedType config.'
        );

        $formData = [
            'password' => [
                'first'  => 'test1234',
                'second' => 'test1234',
            ],
        ];

        $objectToCompare           = new \stdClass();
        $objectToCompare->password = null;
        $object                    = new \stdClass();
        $object->password          = 'test1234';

        $form = $this->factory->create(NewPasswordType::class, $objectToCompare);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $objectToCompare);

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $form->createView()->children);
        }

        $this->assertTrue($form->get('password')->getConfig()->getOption('required'));
        $this->assertInstanceOf(RepeatedType::class, $form->get('password')->getConfig()->getType()->getInnerType());
    }
}
