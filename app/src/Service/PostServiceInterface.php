<?php
/**
 * Post service interface.
 */

namespace App\Service;

use App\Entity\Post;
use App\Entity\Tag;
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
     * @return array posts
     */
    public function getAllPosts(): array;
    //    /**
    //     * Find by title.
    //     *
    //     * @param string $title Tag title
    //     *
    //     * @return Tag|null Tag entity
    //     */
    //    public function findOneByTitle(string $title): ?Tag;

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
