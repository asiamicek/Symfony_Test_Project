<?php
/**
 * Comment Service Test.
 */

namespace Service;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Enum\UserRole;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Service\CommentService;
use App\Service\PostService;
use App\Tests\BaseTest;
use Doctrine\ORM\Exception\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class CommentServiceTest.
 * @property $entityManager
 */
class CommentServiceTest extends BaseTest
{
    /**
     * Comment service.
     */
    private ?CommentService $commentService;

    /**
     * @return void void
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->commentService = $container->get(CommentService::class);
        $this->postService = $container->get(PostService::class);
    }

    public function testGetPaginatedList(): void
    {

        $user = $this->createUser([UserRole::ROLE_USER->value], 'category_user_service1@example.com', 'cus1');
        $category = $this-> CreateCategory('service-com-cat');
        $post = $this-> CreatePost($user, $category);

        // given
        $page = 1;
        $dataSetSize = 5;
        $expectedResultSize = 5;
        $commentRepository =
            static::getContainer()->get(CommentRepository::class);

        $i = 0;
        while ($i < $dataSetSize) {


            $comment = new Comment();
            $comment->setContent('ComText');
            $comment->setAuthor($user);
            $comment->setPost($post);

            $commentRepository = self::getContainer()->get(CommentRepository::class);
            $commentRepository->save($comment, true);

            ++$i;
        }
        // when
        $result = $this->commentService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    public function testFindByPost(): void
    {
        $commentRepository =
            static::getContainer()->get(CommentRepository::class);
        $postRepository =
            static::getContainer()->get(PostRepository::class);

        // Tworzenie testowego posta
        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'post_admin_service3@example.com', 'padmins3');
        $post = new Post();
        $post->setTitle('Test Post');
        $post->setContent('Test content');
        $post->setAuthor($user);
        $this->postService->save($post);

        // Tworzenie testowych komentarzy przypisanych do posta
        $comments = [];
        for ($i = 1; $i <= 5; $i++) {
            $comment = new Comment();
            $comment->setContent("Comment $i");
            $comment->setPost($post);
            $comment->setAuthor($user);
            $this->commentService->save($comment);
            $comments[] = $comment;
        }

        // Wywołanie metody findByPost
        $foundComments = $this->commentService->findByPost($post);

        // Sprawdzenie, czy zwrócona tablica jest poprawna
        $this->assertEquals($comments, $foundComments);
    }


    public function testSave(): void
    {
        $commentRepository =
            static::getContainer()->get(CommentRepository::class);
        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'post_admin_service2@example.com', 'padmins2');
        // Tworzenie testowego komentarza
        $before = $commentRepository->findAll();
        $category = $this->createCategory('testservicesave');
        $post = $this->createPost($user, $category);
        $comment = $this-> createComment($post, $user);
        $comment->setContent('Test Comment');

        // Wywołanie metody save

        $this->commentService->save($comment);
        $after = $commentRepository->findAll();

        $this->assertEquals(count($before), count($after) - 1);
    }


    /**
     * Test delete.
     *
     * @throws ORMException
     */
    public function testDelete(): void
    {
        $commentRepository =
            static::getContainer()->get(CommentRepository::class);

        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value], 'post_admin_service@example.com', 'padmins');
        $category = $this->createCategory('testservicedelete');
        $post = $this->createPost($user, $category);
        $commentToDelete = $this->createComment($post, $user);

        $commentRepository->save($commentToDelete);

        $before = $commentRepository->findAll();

        $this->commentService->delete($commentToDelete);
        $after = $commentRepository->findAll();

        $this->assertEquals(count($before), count($after) + 1);
    }


}
