<?php

namespace App\Test\Controller;

use App\Entity\Category;
use App\Entity\Enum\UserRole;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Tests\BaseTest;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends BaseTest
{
    /**
     * Test route.
     *
     * @const string
     */
    public const TEST_ROUTE = '/category';


    /**
     * Set up tests.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Category::class);
    }

    /**
     * Test index route for Normal user.
     *
     */
    public function testIndexRouteNormalUser(): void
    {
        // given
        $user = $this->createUser([UserRole::ROLE_USER->value], 'category_user1@example.com', 'cu1');
        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

    /**
     * Test index route for admin user.
     *
     */
    public function testIndexRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'category_admin1@example.com', 'ca1');
        $this->httpClient->loginUser($adminUser);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test New category
     */
    public function testNew(): void
    {
        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'category_new_admin@example.com', 'cna');
        $this->httpClient->loginUser($user);

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');
        $result = $this->httpClient->getResponse();

        $this->assertEquals(200, $result->getStatusCode());

        $this->httpClient->submitForm('Zapisz', [
            'category[title]' => 'Testing',
        ]);

        $this->assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testEditCategory(): void
    {
        // given
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $expectedStatusCode = Response::HTTP_OK; // Kod 200 HTTP_OK oznacza sukces
        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value], 'category_admin2@example.com', 'ca2');
        $this->httpClient->loginUser($adminUser);
        $category = $this->createCategory('CN3');
        $categoryRepository->save($category);
        $expectedNewCategoryTitle = 'Test Category Edit';

        $newCategoryData = [
            'title' => 'Updated Category',
        ];


        $this->httpClient->request(
            'GET', self::TEST_ROUTE . '/' .
            $category->getId() . '/edit'
        );

        // when
        $this->httpClient->submitForm(
            'Edytuj',
            ['category' => ['title' => $expectedNewCategoryTitle]]
        );


        // then
        $savedCategory = $categoryRepository->findOneById($category->getId());
        $this->assertEquals($expectedNewCategoryTitle, $savedCategory->getTitle());
    }

}
