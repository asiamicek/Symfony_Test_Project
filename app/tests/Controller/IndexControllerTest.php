<?php

namespace Controller;

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

//    public function testIndex()
//    {
////        $client = static::createClient();
//
//        // Get the post service from the container
//        $postService = self::$container->get(PostServiceInterface::class);
//
//        // Create a user with ROLE_USER and ROLE_ADMIN roles
//        $user = $this->createUser(['ROLE_USER', 'ROLE_ADMIN'], 'index_adminn@example.com', 'ia');
//
//        $category = $this->createCategory('indexcategory');
//
//        // Create a few posts and save them
//        $post1 = $this->createPost($user, $category);
//        // Set up post1 properties
//        $postService->save($post1);
//
//        $post2 = $this->createPost($user, $category);
//        // Set up post2 properties
//        $postService->save($post2);
//
//        // Create an instance of the IndexController and inject the real PostService
//        $indexController = new IndexController($postService);
//
//        // Call the index method directly
//        $response = $indexController->index(new Request());
//
//        // Assert that the response is successful
//        $this->assertTrue($response->isSuccessful());
//
//        // Add more assertions as needed to validate the response content
//    }
}
