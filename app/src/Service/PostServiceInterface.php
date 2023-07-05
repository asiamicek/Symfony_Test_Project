<?php
/**
 * Post service interface.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Post;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface PostServiceInterface.
 */
interface PostServiceInterface
{
    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface;

    /**
     * @param int   $page    page
     * @param array $filters filters
     *
     * @return PaginationInterface paginator
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface;

    /**
     * Find posts by category.
     *
     * @param Category $category Category
     *
     * @return array Array of posts in the category
     */
    public function findByCategory(Category $category): array;

    /**
     * Save entity.
     *
     * @param Post $post Post entity
     */
    public function save(Post $post): void;

    /**
     * Delete entity.
     *
     * @param Post $post Post entity
     */
    public function delete(Post $post): void;
}
