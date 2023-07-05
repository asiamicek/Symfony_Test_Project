<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Category repository.
     */
    private CategoryRepository $categoryRepository;

    /**
     * Post repository.
     */
    private PostRepository $postRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param CategoryRepository $categoryRepository Category repository
     * @param PostRepository     $postRepository     Post repository
     * @param PaginatorInterface $paginator          Paginator
     */
    public function __construct(CategoryRepository $categoryRepository, PostRepository $postRepository, PaginatorInterface $paginator)
    {
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
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
            $this->categoryRepository->queryAll(),
            //            $posts -> categoryRepository->postsAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Delete category.
     *
     * @param \App\Entity\Category $category Category entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }

    /**
     * Find by id.
     *
     * @param int $id Category id
     *
     * @return Category|null Category entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Category
    {
        return $this->categoryRepository->findOneById($id);
    }
}
