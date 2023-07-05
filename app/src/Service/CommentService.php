<?php
/**
 * Comment service.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentService.
 */
class CommentService implements CommentServiceInterface
{
    /**
     * Comment repository.
     */
    private CommentRepository $commentRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param CommentRepository  $commentRepository Comment repository
     * @param PaginatorInterface $paginator         Paginator
     */
    public function __construct(CommentRepository $commentRepository, PaginatorInterface $paginator)
    {
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentRepository->queryAll(),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Finds posts by category.
     *
     * @param Post $post Post entity
     *
     * @return array Array of posts in the category
     */
    public function findByPost(Post $post): array
    {
        return $this->commentRepository->findBy(['post' => $post]);
    }

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void
    {
        //        if (null == $comment->getId()) {
        //            $comment->setCreatedAt(new \DateTimeImmutable());
        //        }
        //        $comment->setUpdatedAt(new \DateTimeImmutable());

        $this->commentRepository->save($comment);
    }

    /**
     * Delete comment.
     *
     * @param \App\Entity\Comment $comment Comment entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }
}
