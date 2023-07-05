<?php

namespace App\Tests\Form\Type;

use App\Entity\Tag;
use App\Entity\User;
use App\Form\Type\TagType;
use App\Form\Type\UserdataType;
use DateTime;
use Symfony\Component\Form\Test\TypeTestCase;
use App\Tests\BaseTest;

class UserdataTypeTest extends TypeTestCase
{
    /**
     * @return void
     */
    public function testSubmitValidDate()
    {

        $formatData = [
            'nickname' => 'TestUser',
            'email' => 'userdata@example.com'
        ];

        $model =  new User();
        $form = $this->factory->create(UserdataType::class, $model);

        $expected = new User();
        $expected->setNickname('TestUser');
        $expected->setEmail('userdata@example.com');
        $form->submit($formatData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected->getNickname(), $model->getNickname());
        $this->assertEquals($expected->getEmail(), $model->getEmail());

    }



}