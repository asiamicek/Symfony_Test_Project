<?php

namespace Type;

use App\Entity\Tag;
use App\Form\Type\TagType;
use DateTime;
use Symfony\Component\Form\Test\TypeTestCase;

class TagTypeTest extends TypeTestCase
{
    /**
     * @return void
     */
    public function testSubmitValidDate()
    {

        $formatData = [
            'name' => 'TestTag',
        ];

        $model = new Tag();
        $form = $this->factory->create(TagType::class, $model);

        $expected = new Tag();
        $expected->setTitle('TestTag');
        $form->submit($formatData);
        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected->getTitle(), $model->getTitle());
        $this->assertEquals($expected->getId(), $model->getId());
    }

}