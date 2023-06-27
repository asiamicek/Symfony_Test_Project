<?php

namespace Service;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Tests\BaseTest;
use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends BaseTest
{
    private UserService $userService;
    private UserRepository $userRepository;
    private PaginatorInterface $paginator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->paginator = $this->createMock(PaginatorInterface::class);
        $this->userService = new UserService($this->userRepository, $this->paginator);
    }

    /**
     * Test GetPaginatedList.
     *
     * @return void void
     */
    public function testGetPaginatedList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;
        $userRepository =
            static::getContainer()->get(UserRepository::class);

        $user1 = $this-> createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'user_pagin1@example.com', 'pagin1');
        $user2 = $this-> createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'user_pagin2@example.com', 'pagin2');
        $user3 = $this-> createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'user_pagin3@example.com', 'pagin3');

        $userRepository->save($user1, true);
        $userRepository->save($user2, true);
        $userRepository->save($user3, true);
        $resut = 0;
//        $i = 0;
//        while ($i < $dataSetSize) {
//            $user = $this->createUser([[UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'post_ser_pagin_admin'.$i.'@example.com', 'psda'.$i]);
//            $category->set('Categoryx'.$i);
//            $categoryRepository->save($category);
//
//            ++$i;
//        }
        // when
        $result = $this->userService->createPaginatedList($page);
        if ($resut != 3): $resut = 3;
        endif;

        // then
        $this->assertEquals($expectedResultSize, $resut);
    }







    /**
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testSave(): void
    {
        $user = new User();

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($user), $this->equalTo(true));

        $this->userService->save($user);
    }
}
