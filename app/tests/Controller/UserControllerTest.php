<?php
/**
 * User controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Enum\UserRole;
use App\Entity\Operation;
use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\CategoryRepository;
use App\Repository\OperationRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Tests\BaseTest;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest.
 */
class UserControllerTest extends BaseTest
{

    /**
     * Test client.
     */
    private const TEST_ROUTE = '/user';

//    /**
//     * Test client.
//     */
//    private KernelBrowser $httpClient;

    /**
     * Set up tests.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test index action as anonymous user.
     */
    public function testIndexActionAsAnonymousUser(): void
    {
        $this->httpClient->request('GET', '/user/');

        $this->assertResponseRedirects('/login');
    }

    /**
     * Test edit password.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    public function testEditPassword(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_ADMIN->value, UserRole::ROLE_USER->value], 'test_edit_password@example.com', 'usereditpass');
        $this->httpClient->loginUser($user);

        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$user->getId().'/edit-password');

        // when
        $this->httpClient->submitForm('Edytuj',
            [
                'user_password' => [
                    'password' => [
                        'first' => 'new_password',
                        'second' => 'new_password',
                    ],
                ],
            ]);

        // then
        $this->assertEquals(302, $this->httpClient->getResponse()->getStatusCode());
    }
}