<?php
/**
 * Comment service interface.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Post;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CommentServiceInterface.
 */
interface CommentServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Finds posts by category.
     *
     * @param Post $post Post entity
     *
     * @return array Array of posts in the category
     */
    public function findByPost(Post $post): array;

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void;

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void;
}
