<?php
/**
 * Post Service Test.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Enum\UserRole;
use App\Entity\Post;
use App\Entity\Tag;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Service\CategoryService;
use App\Service\PostService;
use App\Service\TagService;
use App\Tests\BaseTest;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class PostServiceTest.
 * @property $entityManager
 */
class PostServiceTest extends BaseTest
{
    /**
     * Post service.
     */
    private ?PostService $postService;

    /**
     * @return void void
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
        $container = static::getContainer();
        $this->postService = $container->get(PostService::class);
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Post::class);
        $this->categoryService = $this->createMock(CategoryService::class);
        $this->tagService = $this->createMock(TagService::class);


//        foreach ($this->repository->findAll() as $object) {
//            $this->repository->delete($object, true);
//        }
    }

    /**
     * Test pagination.
     *
     * @throws NonUniqueResultException
     */
    public function testCreatePaginatedList(): void
    {
        // given
        $page = 1;
        $user = $this->createUser([UserRole::ROLE_USER->value], 'post_list@example.com', 'test123');
        $category = $this->createCategory('paginatedlistservis');

        $dataSetSize = 25;
        $counter = 0;
        while ($counter < $dataSetSize) {
            $post = new Post();
            $post->setTitle('Test Post #'.$counter);
            $post->setContent('PContent');
            $post->setAuthor($user);
            $post->setCategory($category);
            $post->setUpdatedAt(DateTimeImmutable::createFromMutable(new \DateTime('@'.strtotime('now'))));
            $post->setCreatedAt(DateTimeImmutable::createFromMutable(new \DateTime('@'.strtotime('now'))));
            $postRepository = self::getContainer()->get(PostRepository::class);
            $postRepository->save($post, true);

            ++$counter;
        }

        // when
        $result = $this->postService->createPaginatedList($page);

        // then
        $this->assertEquals(10, $result->count());
    }


    /**
     * @return void
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testDelete(): void
    {
        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'post_ser_delete_admin@example.com', 'psda');
        $connection = $this->httpClient->loginUser($user);


        $postRepository = static::getContainer()->get(PostRepository::class);
        $commentRepository = static::getContainer()->get(CommentRepository::class);
        $before = $postRepository->findAll();


        $category = $this->createCategory('cattestselpost');
        $postToDelete = $this->createPost($user, $category);

        $postRepository->save($postToDelete);

//        // Usuń powiązane komentarze
//        $newcomment = $this->createComment($postToDelete, $user);
//        $commentRepository->save($newcomment);
//
//        $comments = $postToDelete->getComments();
//        foreach ($comments as $comment) {
//            $postToDelete->removeComment($comment);
//        }
        $comments = $commentRepository->findBy(['post' => $postToDelete]);

        foreach ($comments as $comment) {
            $commentRepository->delete($comment);
        }

        // Usuwanie głównego wiersza
        $this->postService->delete($postToDelete);

        $postRepository->delete($postToDelete);
        $after = $postRepository->findAll();

        $this->assertEquals(count($before), count($after) + 0);

//
//        $connection->executeQuery('SET foreign_key_checks = 1');
    }


//    /**
//     * @throws NonUniqueResultException
//     */
//    public function testPrepareFilters(): void
//    {
//
//        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'post_ser_filtre_admin@example.com', 'psfa');
//        $category = $this->createCategory('categorypreparetest');
//        $filters['category_id'] = $category->getId();
//        $tag = new Tag();
////        $tag -> setAuthor($user);
//        $tag->setTitle('newtag');
//        $filters['tags_id'] = $tag->getId();
//
//        $this->categoryService->expects($this->once())
//            ->method('findOneById')
//            ->with($filters['category_id'])
//            ->willReturn($category);
//
//        $this->tagService->expects($this->once())
//            ->method('findOneById')
//            ->with($filters['tags_id'])
//            ->willReturn($tag);
//
//        $result = $this->postService->prepareFilters($filters);
//
//        $this->assertSame($category, $result['category']);
//        $this->assertSame($tag, $result['tag']);
//    }



}
