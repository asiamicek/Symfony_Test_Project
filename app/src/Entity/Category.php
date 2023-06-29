<?php
/**
 * Category entity.
 */

namespace App\Entity;

use DateTimeImmutable;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

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
    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 64)]
    private ?string $title = null;

    /**
     * Posts collection.
     *
     * @ORM\Column(nullable=true)
     *
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Post::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'category_id', nullable: true)]
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
     * @param string $title Title
     *
     * @return Category
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param int                    $categoryId    category id
     * @param EntityManagerInterface $entityManager EntityManager
     *
     * @return bool bool
     */
    public static function checkPostsByCategoryId(int $categoryId, EntityManagerInterface $entityManager): bool
    {
        $entityManager = // Pobierz EntityManager
        $repository = $entityManager->getRepository(Category::class);

        return $repository->checkPostsByCategoryId($categoryId);
    }

    /**
     * Getter for Posts.
     *
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * Add Post function.
     *
     * @param Post $post Post
     *
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
     * Remove Post function.
     *
     * @param Post $post Post
     *
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
