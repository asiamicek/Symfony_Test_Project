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

//    /**
//     * Test show single category - AKTUALNE.
//     *
//     *
//     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
//     */
//    public function testShowCategory(): void
//    {
//        // given
//        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'category_show_admin@example.com', 'csa');
//        $this->httpClient->loginUser($user);
//        $expectedCategory = new Category();
//        $expectedCategory->setTitle('Test category');
//        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
//        $categoryRepository->save($expectedCategory);
//
//        // when
//        $this->httpClient->request('GET', self::TEST_ROUTE . '/' . $expectedCategory->getId());
//        $result = $this->httpClient->getResponse();
//
//        // then
//        $this->assertEquals(200, $result->getStatusCode());
//        $this->assertSelectorTextContains('td', $expectedCategory->getId());
//    }


//    /**
//     * Test show category MOJE AKTUALNE
//     */
//    public function testShowCategory(): void
//    {
//        // given
//        $user = $this->createUser([UserRole::ROLE_USER->value], 'category_show_user@example.com', 'csu');
//        $this->httpClient->loginUser($user);
//
//        // Create a category
//        $category = new Category();
//        $category->setTitle('Test 2 category');
//
//        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
//        $categoryRepository->save($category);
//
////        // Create a post associated with the category
////        $post = new Post();
////        $post->setTitle('My Post');
////        $post->setContent('This is my post content');
////        $post->setCategory($category);
////        $post->setAuthor($user);
//
//        // Save the category and post in the database
////        $entityManager = self::getContainer()->get('doctrine')->getManager();
////        $entityManager->persist($category);
////        $entityManager->persist($post);
////        $entityManager->flush();
//
//        // when
//        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$category->getId());
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals(Response::HTTP_OK, $resultStatusCode);
//        $this->assertSelectorTextContains('html h1', $category->getTitle());
//        // Add additional assertions to check the response content or other relevant data
//    }





//    public function testCreateCategory(): void
//    {
//        // given
//        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value], 'admin3@example.com', 'admin3');
//        $this->httpClient->loginUser($adminUser);
//
//        // when
//        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals(Response::HTTP_OK, $resultStatusCode);
//        // Add additional assertions to check the response content or other relevant data
//
//        // Check if user is not admin
//        $user = $this->createUser([UserRole::ROLE_USER->value], 'user@example.com', 'user');
//        $this->httpClient->loginUser($user);
//
//        // when trying to create category as non-admin user
//        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals(Response::HTTP_FOUND, $resultStatusCode);
//        $this->assertContains('message_record_not_found', $this->httpClient->followRedirect()->getContent());
//        // Add additional assertions to check the flash message and redirection
//    }



//    /**
//     * Test creating a new post.
//     */
//    public function testCreatePost(): void
//    {
//        // given
//        $expectedStatusCode = 302; // redirect after successful creation
//        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value, UserRole::ROLE_USER->value], 'post_admin@example.com', 'pa');
//        $this->httpClient->loginUser($adminUser);
//        $category = $this->createCategory('CN1');
//        $categoryRepository = self::getContainer()->get(CategoryRepository::class);
//
//        // Get the original number of categories
//        $originalNumCategories = count($categoryRepository->findAll());
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
//        // Check if the number of categories increased by 1
//        $this->assertSame($originalNumCategories + 1, count($categoryRepository->findAll()));
//        // Add additional assertions to check if the post was created successfully
//    }

//    /**
//     * Test creating a new comment.
//     */
//    public function testCreateComment(): void
//    {
//        // given
//        $expectedStatusCode = 302; // redirect after successful creation
//        $user = $this->createUser([UserRole::ROLE_USER->value], 'comment_user@example.com', 'cu');
//        $this->httpClient->loginUser($user);
//        $category = $this->createCategory('CN2');
//        $post = $this->createPost($user, $category);
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

//    /**
//     * Test editing a category.
//     */
//    public function testEditCategory(): void
//    {
//        // given
//        $expectedStatusCode = 200;
//        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value], 'category_admin2@example.com', 'ca2');
//        $this->httpClient->loginUser($adminUser);
//        $category = $this->createCategory('CN3');
//
//        // when
//        $newCategoryData = [
//            'title' => 'Updated Category',
//        ];
//        $this->httpClient->request('POST', self::TEST_ROUTE.'/'.$category->getId().'/edit', $newCategoryData);
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals($expectedStatusCode, $resultStatusCode);
//        // Add additional assertions to check if the category was edited successfully
//    }

//    /**
//     * Test deleting a category.
//     */
//    public function testDeleteCategory(): void
//    {
//        // given
//        $expectedStatusCode = 302; // redirect after successful deletion
//        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value], 'category_admin3@example.com', 'ca3');
//        $this->httpClient->loginUser($adminUser);
//        $category = $this->createCategory('CN4');
//
//        // when
//        $this->httpClient->request('DELETE', self::TEST_ROUTE.'/'.$category->getId().'/delete');
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals($expectedStatusCode, $resultStatusCode);
//        // Add additional assertions to check if the category was deleted successfully
//    }


//    public function testCreatePost(): void
//    {
//        // given
//        $expectedStatusCode = Response::HTTP_FOUND; // Kod 302 HTTP_FOUND oznacza przekierowanie
//        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value, UserRole::ROLE_USER->value], 'post_admin@example.com', 'pa');
//        $this->httpClient->loginUser($adminUser);
//        $category = $this->createCategory('CN1');
//        $categoryRepository = self::getContainer()->get(CategoryRepository::class);
//
//        // Get the original number of categories
//        $originalNumCategories = count($categoryRepository->findAll());
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
//        // Check if the number of categories increased by 1
//        $this->assertSame($originalNumCategories + 1, count($categoryRepository->findAll()));
//        // Add additional assertions to check if the post was created successfully
//    }


//    public function testCreateComment(): void
//    {
//        // given
//        $expectedStatusCode = Response::HTTP_FOUND; // Kod 302 HTTP_FOUND oznacza przekierowanie
//        $user = $this->createUser([UserRole::ROLE_USER->value], 'comment_user@example.com', 'cu');
//        $this->httpClient->loginUser($user);
//        $category = $this->createCategory('CN2');
//        $post = $this->createPost($user, $category);
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
//        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$category->getId().'/edit', $newCategoryData);

        $this->httpClient->request(
            'GET', self::TEST_ROUTE.'/'.
            $category->getId().'/edit'
        );

        // when
        $this->httpClient->submitForm(
            'Edytuj',
            ['category' => ['title' => $expectedNewCategoryTitle]]
        );

//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $savedCategory = $categoryRepository->findOneById($category->getId());
        $this->assertEquals($expectedNewCategoryTitle, $savedCategory->getTitle());

//        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        // Add additional assertions to check if the category was edited successfully
    }

//    /**
//AKTUALNE POWOSUJE BŁĄD

//     * @throws ContainerExceptionInterface
//     * @throws NotFoundExceptionInterface
//     */
//    public function testDeleteCategory(): void
//    {
//        // given
//        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value], 'category_admin_delete@example.com', 'cadelete');
//        $this->httpClient->loginUser($adminUser);
//
//        $categoryRepository =
//            static::getContainer()->get(CategoryRepository::class);
//
//        $testCategory = new Category();
//        $testCategory->setTitle('TestCategoryCreated');
//        $categoryRepository->save($testCategory);
//        $testCategoryId = $testCategory->getId();
//
//        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$testCategoryId.'/delete');
//
//        // when
//        $this->httpClient->submitForm(
//            'Usuń'
//        );
//
//        // then
//        $this->assertNull($categoryRepository->findOneById($testCategoryId));
//    }


//    public function testDeleteCategory(): void
//    {
//        // Tworzenie klienta do testowania
//        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value], 'category_admin3@example.com', 'ca3');
//        $this->httpClient->loginUser($adminUser);
//
//        // Tworzenie kategorii
//        $category = $this->createCategory('Test Category');
//        $categoryId = $category->getId();
//
//        // Tworzenie postu przypisanego do kategorii
//        $post = $this->createPost($adminUser, $category);
//
//        // Wykonanie żądania usuwania kategorii
//        $crawler = $this->httpClient->request('GET', '/category/'.$categoryId.'/delete');
//
//        // Sprawdzenie statusu odpowiedzi
//        $this->assertEquals(200, $this->httpClient->getResponse()->getStatusCode());
//
//        // Sprawdzenie, czy formularz usunięcia jest wyświetlony
//        $this->assertSelectorExists('form[name="delete_category"]', $crawler);
//
//        // Wykonanie żądania usuwania kategorii (metoda DELETE)
//        $this->httpClient->submitForm('Delete', []);
//
//        // Sprawdzenie statusu odpowiedzi po przesłaniu formularza
//        $this->assertEquals(302, $this->httpClient->getResponse()->getStatusCode());
//
//        // Sprawdzenie przekierowania na stronę kategorii po usunięciu
//        $this->httpClient->followRedirect();
//        $this->assertRouteSame('category_index');
//
//        // Sprawdzenie komunikatu flash o sukcesie
//        $this->assertContains('message.deleted_successfully', $this->httpClient->getResponse()->getContent());
//
//        // Sprawdzenie, czy kategoria została usunięta
//        $categoryRepository = self::$container->get(CategoryRepository::class);
//        $categoryAfterDelete = $categoryRepository->find($categoryId);
//        $this->assertNull($categoryAfterDelete);
//
//        // Sprawdzenie, czy powiązane posty zostały usunięte
//        $postRepository = self::$container->get(PostRepository::class);
//        $postAfterDelete = $postRepository->find($post->getId());
//        $this->assertNull($postAfterDelete);
//
//        // Sprawdzenie komunikatu flash o istnieniu powiązanych postów
//        $this->assertContains('message_category_contains_posts', $this->httpClient->getResponse()->getContent());
//    }
//AKTUALNA
//    public function testDeleteCategory(): void
//    {
//        // given
//        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
//        $expectedStatusCode = Response::HTTP_FOUND; // Kod 302 HTTP_FOUND oznacza przekierowanie
//
//        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value], 'category_admin3@example.com', 'ca3');
//        $this->httpClient->loginUser($adminUser);
//
//        $category = new Category();
//        $category ->setTitle('CN4');
//        $categoryRepository->save($category);
//        $categoryId = $category->getId();
//
//        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$categoryId.'/delete');
//
//        // when
//        $this->httpClient->submitForm('Usuń');
//
//        // then
//        $this->assertNull($categoryRepository->findOneByTitle('CN4'));
/////////////////////////////
//        // when
//        $this->httpClient->request('DELETE', self::TEST_ROUTE.'/'.$category->getId().'/delete');
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals($expectedStatusCode, $resultStatusCode);

        // You can add additional assertions to check if the category was deleted successfully
//    }


}
