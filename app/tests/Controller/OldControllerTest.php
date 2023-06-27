<?php
///**
// * Category Controller test.
// */
//
//namespace App\Tests\Controller;
//
//use App\Entity\Category;
//use App\Entity\Enum\UserRole;
//use App\Entity\Comment;
//use App\Entity\User;
//use App\Entity\Post;
//use App\Repository\CategoryRepository;
//use App\Repository\CommentRepository;
//use App\Repository\UserRepository;
//use App\Repository\PostRepository;
//use Doctrine\ORM\OptimisticLockException;
//use Doctrine\ORM\ORMException;
//use Psr\Container\ContainerExceptionInterface;
//use Psr\Container\NotFoundExceptionInterface;
//use Symfony\Bundle\FrameworkBundle\KernelBrowser;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//
///**
// * Class CategoryControllerTest.
// */
//class CategoryControllerTest extends WebTestCase
//{
//    /**
//     * Test route.
//     *
//     * @const string
//     */
//    public const TEST_ROUTE = '/category';
//
//    /**
//     * Test client.
//     */
//    private KernelBrowser $httpClient;
//
//    /**
//     * Set up tests.
//     */
//    public function setUp(): void
//    {
//        $this->httpClient = static::createClient();
//    }
//
//    /**
//     * Test index route for anonymous user.
//     */
//    public function testIndexRouteAnonymousUser(): void
//    {
//        // given
//        $expectedStatusCode = 302; // redirect to login page
//
//        // when
//        $this->httpClient->request('GET', self::TEST_ROUTE);
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals($expectedStatusCode, $resultStatusCode);
//    }
//
//    /**
//     * Test index route for admin user.
//     */
//    public function testIndexRouteAdminUser(): void
//    {
//        // given
//        $expectedStatusCode = 200;
//        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value, UserRole::ROLE_USER->value]);
//        $this->httpClient->loginUser($adminUser);
//
//        // when
//        $this->httpClient->request('GET', self::TEST_ROUTE);
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals($expectedStatusCode, $resultStatusCode);
//    }
//
//    /**
//     * Test show single category.
//     */
//    public function testShowCategory(): void
//    {
//        // given
//        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value, UserRole::ROLE_USER->value]);
//        $this->httpClient->loginUser($adminUser);
//
//        $expectedCategory = new Category();
//        $expectedCategory->setTitle('Test 2 category');
//        $expectedCategory->setAuthor($adminUser);
//        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
//        $categoryRepository->save($expectedCategory);
//
//        // when
//        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$expectedCategory->getId());
//        $result = $this->httpClient->getResponse();
//
//        // then
//        $this->assertEquals(200, $result->getStatusCode());
//        $this->assertSelectorTextContains('html h1', $expectedCategory->getTitle());
//    }
//
//    /**
//     * Create user.
//     *
//     * @param array $roles User roles
//     *
//     * @return User User entity
//     */
//    private function createUser(array $roles): User
//    {
//        $user = new User();
//        $user->setRoles($roles);
//        // Set any other required fields for the user
//
//        return $user;
//    }
//
//    /**
//     * Test creating a new post.
//     */
//    public function testCreatePost(): void
//    {
//        // given
//        $expectedStatusCode = 302; // redirect after successful creation
//        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value, UserRole::ROLE_USER->value]);
//        $this->httpClient->loginUser($adminUser);
//        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
//        $category = $categoryRepository->find(1); // replace 1 with the ID of the desired category for the post
//
//        // when
//        $postData = [
//            'title' => 'Test Post',
//            'content' => 'This is a test post.',
//        ];
//        $this->httpClient->request('POST', self::TEST_ROUTE.'/'.$category->getId().'/post/create', $postData);
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals($expectedStatusCode, $resultStatusCode);
//        // Add additional assertions to check if the post was created successfully
//    }
//
//    /**
//     * Test creating a new comment.
//     */
//    public function testCreateComment(): void
//    {
//        // given
//        $expectedStatusCode = 302; // redirect after successful creation
//        $user = $this->createUser([UserRole::ROLE_USER->value]); // replace with the desired user
//        $this->httpClient->loginUser($user);
//        $postRepository = static::getContainer()->get(PostRepository::class);
//        $post = $postRepository->find(1); // replace 1 with the ID of the desired post for the comment
//
//        // when
//        $commentData = [
//            'content' => 'This is a test comment.',
//        ];
//        $this->httpClient->request('POST', self::TEST_ROUTE.'/'.$post->getId().'/comment/create', $commentData);
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals($expectedStatusCode, $resultStatusCode);
//        // Add additional assertions to check if the comment was created successfully
//    }
//
//}