<?php

namespace Controller;

use App\Entity\Enum\UserRole;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Tests\BaseTest;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class PostControllerTest extends BaseTest
{
    /**
     * Test route.
     *
     * @const string
     */
    public const TEST_ROUTE = '/post';

//    private PostRepository $repository;

    /**
     * Set up tests.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Post::class);
    }

    /**
     * Test index route
     *
     * @return void
     */
    public function testIndex(): void
    {
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'post_index_admin1@example.com', 'pindex1');
        $this->httpClient->loginUser($adminUser);
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $result = $this->httpClient->getResponse();

        $this->assertEquals($expectedStatusCode, $result->getStatusCode());
    }


    /**
     * Test index route for admin user.
     *
     */
    public function testIndexRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value, UserRole::ROLE_USER->value], 'test_operation_admin@example.com', 'user111');
        $this->httpClient->loginUser($adminUser);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $result = $this->httpClient->getResponse();

        // then
        $this->assertEquals($expectedStatusCode, $result->getStatusCode());
    }

    /**
     * Test new post
     *
     * @return void
     */
    public function testNew(): void
    {

        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'post_new_admin1@example.com', 'pnewa1');
        $this->httpClient->loginUser($user);

        $originalNumObjectsInRepository = count($this->repository->findAll());
        $category = $this->createCategory('newpostcat');

        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');
        $result = $this->httpClient->getResponse();

        $this->assertEquals(200, $result->getStatusCode());

        // Sprawdź, czy pola formularza mają przypisane wartości
        $formData = [
            'post[title]' => 'Testing',
            'post[content]' => 'Testing',
            'post[category]' => $category->getId(),
            'post[tags]' => 'Testing',
        ];

        $this->assertNotEmpty($formData['post[title]']);
        $this->assertNotEmpty($formData['post[content]']);
        $this->assertNotEmpty($formData['post[category]']);

// Prześlij formularz tylko jeśli wszystkie pola mają wartości

        $this->httpClient->submitForm('Zapisz', $formData);
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        $this->assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
//        $this->assertEquals(302, $resultStatusCode);
    }

    /**
     * @return void
     */
    public function testShow(): void
    {
        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'post_show_admin2@example.com', 'pshoa2');
        $category = $this->createCategory('postshowcat');
        $fixture = $this->createPost($user, $category);

        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$fixture->getId());

        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        $this->assertEquals(200, $resultStatusCode);
        $this->assertEquals('post_show_admin2@example.com', $fixture->getAuthor()->getEmail());
        $this->assertEquals('pshoa2', $fixture->getAuthor()->getNickname());
        $this->assertGreaterThanOrEqual($fixture->getUpdatedAt(),$fixture->getCreatedAt());
    }
}
