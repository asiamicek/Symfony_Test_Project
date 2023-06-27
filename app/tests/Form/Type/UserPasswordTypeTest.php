<?php
//
//namespace Type;
//
//use App\Entity\Tag;
//use App\Entity\User;
//use App\Form\Type\TagType;
//use App\Form\Type\UserPasswordType;
//use App\Form\UserdataType;
//use DateTime;
//use Symfony\Component\Form\Test\TypeTestCase;
//use App\Tests\BaseTest;
//
//class UserPasswordTypeTest extends TypeTestCase
//{
//    /**
//     * @return void
//     */
//    public function testSubmitValidDate()
//    {
//
//        $formatData = [
//            'password' => 'p@55w0rd',
//        ];
//
//        $model =  new User();
//        $form = $this->factory->create(UserPasswordType::class, $model);
//
//        $expected = new User();
//        $expected->setPassword('p@55w0rd');
//
//        $form->submit($formatData);
//
//        $this->assertTrue($form->isSynchronized());
//
//        $this->assertEquals($expected->getPassword(), $model->getPassword());
//
//
//
//        //
////        $passwordHasher = static::getContainer()->get('security.password_hasher');
////        $user = new User();
////        $user->setEmail($email);
////        $user->setRoles($roles);
////        $user->setNickname($nickname);
////        $user->setPassword(
////            $passwordHasher->hashPassword(
////                $user,
////                'p@55w0rd'
////            )
////        );
////        $userRepository = static::getContainer()->get(UserRepository::class);
////        $userRepository->save($user, true);
//    }
//
//
//
//}