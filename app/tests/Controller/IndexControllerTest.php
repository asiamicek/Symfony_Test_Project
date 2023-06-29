<?php

namespace App\Tests\Controller;

use App\Controller\IndexController;
use App\Entity\Enum\UserRole;
use App\Entity\Post;
use App\Service\PostService;
use App\Service\PostServiceInterface;
use App\Service\UserService;
use App\Tests\BaseTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;


class IndexControllerTest extends BaseTest
{
    /**
    //     * Test route.
    //     *
    //     * @const string
    //     */
    public const TEST_ROUTE = '/';

    public function setUp(): void
    {
        $this->httpClient = static::createClient();
        $container = static::getContainer();
        $this->postService = $container->get(PostService::class);
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Post::class);
    }

    /**
     * Test index route for Normal user.
     *
     */
    public function testIndexRoute(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value], 'indexuser1@example.com', 'iu1');
        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

}
