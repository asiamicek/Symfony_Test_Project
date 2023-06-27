<?php
/**
 * Category entity.
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Category.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
#[ORM\UniqueConstraint(name: 'uq_categories_title', columns: ['title'])]
#[UniqueEntity(fields: ['title'])]
class Category
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Title.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string',length: 64)]
    private ?string $title = null;

    /**
     * Posts collection.
     * @ORM\Column(nullable=true)
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Post::class)]
    #[ORM\JoinColumn(name: "id", referencedColumnName: "category_id", nullable: true)]
    private Collection $posts;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string|null $title Title
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public static function checkPostsByCategoryId(int $categoryId, EntityManagerInterface $entityManager): bool
    {
        $entityManager = // Pobierz EntityManager
        $repository = $entityManager->getRepository(Category::class);

        return $repository->checkPostsByCategoryId($categoryId);
    }

    /**
     * Getter for Posts
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * Add Post function
     *
     * @param Post $post
     * @return $this
     */
    public function addComment(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCategory($this);
        }

        return $this;
    }

    /**
     * Remove Post function
     *
     * @param Post $post
     * @return $this
     */
    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }
}
