<?php
/**
 * Tag service tests.
 */

namespace Service;

use App\Entity\Enum\UserRole;
use App\Entity\Tag;
use App\Service\TagService;
use App\Service\TagServiceInterface;
use App\Tests\BaseTest;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class TagServiceTest.
 */
class TagServiceTest extends BaseTest
{
    /**
     * Tag repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Tag service.
     */
    private ?TagServiceInterface $tagService;

    /**
     * Set up test.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->tagService = $container->get(TagService::class);
    }

    /**
     * Test pagination empty list.
     */
    public function testGetPaginatedList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 20;
        $expectedResultSize = 10;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $tag = new Tag();
            $tag->setTitle('Test Tag #' . $counter);
            $this->tagService->save($tag);

            ++$counter;
        }

        // when
        $result = $this->tagService->getPaginatedList($page);

        // then
        $this->assertEquals(5, $result->count());
    }

    /**
     * Test save.
     *
     * @throws ORMException
     */
    public function testSave(): void
    {
        // given
//        $user = $this->createUser([UserRole::ROLE_USER->value], 'tag_user1@example.com', 'tu1');
//        $this->httpClient->loginUser($user);

        $expectedTag = new Tag();
        $expectedTag->setTitle('Test1');
//        $expectedTag->setAuthor($user);

        // when
        $this->tagService->save($expectedTag);

        // then
        $expectedTagId = $expectedTag->getId();
        $resultTag = $this->entityManager->createQueryBuilder()
            ->select('tag')
            ->from(Tag::class, 'tag')
            ->where('tag.id = :id')
            ->setParameter(':id', $expectedTagId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedTag, $resultTag);
    }

    /**
     * Test delete.
     *
     * @throws ORMException
     */
    public function testDelete(): void
    {
        // given


//        $user = $this->createUser([UserRole::ROLE_USER->value], 'tag_user2@example.com', 'tu2');
//        $this->httpClient->loginUser($user);

        $tagToDelete = new Tag();
        $tagToDelete->setTitle('Test2');
//        $tagToDelete->setAuthor($user);

        $this->entityManager->persist($tagToDelete);
        $this->entityManager->flush();
        $deletedTagId = $tagToDelete->getId();

        // when
        $this->tagService->delete($tagToDelete);

        // then
        $resultTag = $this->entityManager->createQueryBuilder()
            ->select('tag')
            ->from(Tag::class, 'tag')
            ->where('tag.id = :id')
            ->setParameter(':id', $deletedTagId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultTag);
    }

    /**
     * Test find by id.
     */
    public function testFindById(): void
    {
        // given
//        $user = $this->createUser([UserRole::ROLE_USER->value], 'tag_user3@example.com', 'tu3');
//        $this->httpClient->loginUser($user);

        $expectedTag = new Tag();
        $expectedTag->setTitle('Test3');
//        $expectedTag->setAuthor($user);

        $this->entityManager->persist($expectedTag);
        $this->entityManager->flush();
        $expectedTagId = $expectedTag->getId();

        // when
        $resultTag = $this->tagService->findOneById($expectedTagId);

        // then
        $this->assertEquals($expectedTag, $resultTag);
    }

    /**
     * Test find by id.
     */
    public function testFindOneByTitle(): void
    {
        // given
//        $user = $this->createUser([UserRole::ROLE_USER->value], 'tag_user4@example.com', 'tu4');
//        $this->httpClient->loginUser($user);

        $expectedTag = new Tag();
        $expectedTag->setTitle('Test4');
//        $expectedTag->setAuthor($user);

        $this->entityManager->persist($expectedTag);
        $this->entityManager->flush();
        $expectedTagId = $expectedTag->getTitle();

        // when
        $resultTag = $this->tagService->findOneByTitle($expectedTagId);

        // then
        $this->assertEquals($expectedTag, $resultTag);
    }




    // other tests for paginated list
}