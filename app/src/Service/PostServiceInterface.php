<?php
/**
 * Post service interface.
 */

namespace App\Service;

use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface PostServiceInterface.
 */
interface PostServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

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
