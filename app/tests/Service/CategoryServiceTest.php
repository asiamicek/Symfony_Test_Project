<?php
/**
 * Category Service Test.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CategoryServiceTest.
 * @property $entityManager
 */
class CategoryServiceTest extends WebTestCase
{
    /**
     * Category service.
     */
    private ?CategoryService $categoryService;

    /**
     * Test entity manager.
     *
     * @var EntityManagerInterface|object|null
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * @return void void
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->categoryService = $container->get(CategoryService::class);
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Category::class);

    }

    /**
     * Delete test.
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testDelete(): void
    {
        // given
        $categoryToDelete = new Category();
        $categoryToDelete->setTitle('Test Category');
        $this->entityManager->persist($categoryToDelete);
        $this->entityManager->flush();
        $deletedCategoryId = $categoryToDelete->getId();

        // when
        $this->categoryService->delete($categoryToDelete);

        // then
        $resultCategory = $this->entityManager->createQueryBuilder()
            ->select('category')
            ->from(Category::class, 'category')
            ->where('category.id = :id')
            ->setParameter('id', $deletedCategoryId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultCategory);
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
        $dataSetSize = 6;
        $expectedResultSize = 10;
        $categoryRepository =
            static::getContainer()->get(CategoryRepository::class);

        $i = 0;
        while ($i < $dataSetSize) {
            $category = new Category();
            $category->setTitle('Categoryx'.$i);
            $categoryRepository->save($category);

            ++$i;
        }
        // when
        $result = $this->categoryService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    public function testFindOneById(): void
    {
        // Tworzenie testowej kategorii
        $category = new Category();
        $category->setTitle('Test Category');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        // Pobranie ID utworzonej kategorii
        $categoryId = $category->getId();

        // Wywołanie metody findOneById
        $foundCategory = $this->categoryService->findOneById($categoryId);

        // Sprawdzenie, czy znaleziona kategoria nie jest nullem
        $this->assertNotNull($foundCategory);

        // Sprawdzenie, czy znaleziona kategoria ma poprawne ID i tytuł
        $this->assertEquals($categoryId, $foundCategory->getId());
        $this->assertEquals('Test Category', $foundCategory->getTitle());
    }
}
