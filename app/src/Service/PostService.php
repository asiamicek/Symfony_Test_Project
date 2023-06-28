<?php
/**
 * Post service.
 */

namespace App\Service;

use App\Entity\Post;
use App\Entity\Tag;
use App\Repository\PostRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PostService.
 */
class PostService implements PostServiceInterface
{
    /**
     * Post repository.
     */
    private PostRepository $postRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Category service.
     */
    private CategoryServiceInterface $categoryService;

    /**
     * Tag service.
     */
    private TagServiceInterface $tagService;

    /**
     * Constructor.
     *
     * @param PostRepository           $postRepository  Post repository
     * @param CategoryServiceInterface $categoryService Category service
     * @param PaginatorInterface       $paginator       Paginator
     * @param TagServiceInterface      $tagService      Tag service
     */
    public function __construct(PostRepository $postRepository, CategoryServiceInterface $categoryService, PaginatorInterface $paginator, TagServiceInterface $tagService)
    {
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     *
     * @throws NonUniqueResultException
     */
    public function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['category_id'])) {
            $category = $this->categoryService->findOneById($filters['category_id']);
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (!empty($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }

    /**
     * Get paginated list.
     *
     * @param int                $page    Page number
     * @param array<string, int> $filters Filters
     *
     * @return PaginationInterface Paginated list
     *
     * @throws NonUniqueResultException
     */
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->postRepository->queryAll($filters),
            $page,
            PostRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }// end createPaginatedList()

    /**
     * Get paginated list.
     *
     * @param int                $page    Page number
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<string, mixed> Paginated list
     *
     * @throws NonUniqueResultException
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->postRepository->queryAll($filters),
            $page,
            PostRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * @return array posts
     */
    public function getAllPosts(): array
    {
        return $this->postRepository->findAll();
    }

    /**
     * Save entity.
     *
     * @param Post $post Post entity
     */
    public function save(Post $post): void
    {
        //        if (null == $post->getId()) {
        //            $post->setCreatedAt(new \DateTimeImmutable());
        //        }
        //        $post->setUpdatedAt(new \DateTimeImmutable());

        $this->postRepository->save($post);
    }

    /**
     * Delete post.
     *
     * @param \App\Entity\Post $post Post entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Post $post): void
    {
        $this->postRepository->delete($post);
    }
}
