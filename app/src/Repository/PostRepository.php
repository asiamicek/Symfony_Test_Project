<?php
/**
 * Post Repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * Class Post Repository.
 *
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Save entity.
     *
     * @param Post $post Post entity
     */
    public function save(Post $post): void
    {
        $this->_em->persist($post);
        $this->_em->flush();
    }

    /**
     * Remove entity.
     *
     * @param Post $entity post
     * @param bool $flush  bool
     */
    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Delete entity.
     *
     * @param Post $post Post entity
     */
    public function delete(Post $post): void
    {
        $this->_em->remove($post);
        $this->_em->flush();
    }

    /**
     * Query all records.
     *
     * @param array<string, object> $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial post.{id, createdAt, updatedAt, title, author}',
                'partial category.{id, title}',
                'partial tags.{id, title}',
                'partial user.{id, nickname}'

            )
            ->join('post.category', 'category')
//            ->leftJoin('post.comments', 'comments')
            ->leftJoin('post.tags', 'tags')
            ->leftJoin('post.author', 'user')
            ->orderBy('post.updatedAt', 'DESC');
        // join post i comment
//        $queryBuilder = $this->getOrCreateQueryBuilder()
//            ->select(
//                'partial post.{id, createdAt, updatedAt, title}',
//                'partial category.title',
//                'partial tags.title'
//            )
//            ->join('post.category', 'category')
//            ->leftJoin('post.tags', 'tags')
//            ->orderBy('post.updatedAt', 'DESC');


        return $this->applyFiltersToList($queryBuilder, $filters);
    }

//    /**
//     * Query tasks by author.
//     *
//     * @param User $user User entity
//     *
//     * @return QueryBuilder Query builder
//     */
//    public function queryByAuthor(User $user): QueryBuilder
//    {
//        $queryBuilder = $this->queryAll([]);
//
//        $queryBuilder->andWhere('post.author = :author')
//            ->setParameter('author', $user);
//
//        return $queryBuilder;
//    }

    /**
     * Query tasks by author.
     *
     * @param User $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryPosts(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()->select(
            'partial post.{id}',
            'partial category.{id}'
        )->leftJoin('post.category', 'category');
    }



//    /**
//     * Query tasks by author.
//     *
//     * @param Category $category Category entity
//     *
//     * @return QueryBuilder Query builder
//     */
//    public function queryByCategory(Category $category): QueryBuilder
//    {
//        $queryBuilder = $this->queryAll([]);
//
//        $queryBuilder->andWhere('post.category = :category')
//            ->setParameter('category', $category);
//
//        return $queryBuilder;
//    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder          $queryBuilder Query builder
     * @param array<string, object> $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category']);
        }

        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }

        return $queryBuilder;
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('post');
    }
}
