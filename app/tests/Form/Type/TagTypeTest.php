<?php

namespace App\Tests\Form\Type;

use App\Entity\Tag;
use App\Form\Type\TagType;
use Symfony\Component\Form\Test\TypeTestCase;

class TagTypeTest extends TypeTestCase
{
    /**
     * @return void
     */
    public function testSubmitValidDate():void
    {
        $formData = [
            'title' => 'TestTag',
        ];

        $model = new Tag();
        $form = $this->factory->create(TagType::class, $model);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $this->assertEquals('TestTag', $model->getTitle());
    }
}
