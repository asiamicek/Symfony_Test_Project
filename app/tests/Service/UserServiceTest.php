<?php

namespace App\Tests\Service;

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
