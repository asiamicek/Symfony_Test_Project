<?php
/**
 * Post entity.
 */

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Post.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: 'posts')]
class Post
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Title.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    private ?string $title = null;

    /**
     * Content.
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    private ?string $content = null;

    /**
     * Created at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Updated at.
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Category.
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category;

    //    /**
    //     * Comments collection.
    //     *
    //     * @var Collection|ArrayCollection
    //     */
    //    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, fetch: 'EXTRA_LAZY')]
    //    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'post_id', onDelete: 'CASCADE')]
    //    private Collection $comments;

    /**
     * Tags.
     *
     * @var ArrayCollection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'posts_tags')]
    private Collection $tags;

    /**
     * Author.
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author;

    /**
     * Comments constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

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
     * @return Post
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter for content.
     *
     * @return string|null content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @param string $content content
     *
     * @return $this this
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for created at.
     *
     * @return \DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param \DateTimeImmutable $createdAt Created at
     *
     * @return Post
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for updated at.
     *
     * @return \DateTimeImmutable|null Updated at
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Setter for updated at.
     *
     * @param \DateTimeImmutable $updatedAt Updated at
     *
     * @return Post
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Getter for category.
     *
     * @return Category|null category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category.
     *
     * @param ?Category $category
     *
     * @return $this
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    //    /**
    //     * Getter for Comments.
    //     *
    //     * @return Collection<int, Comment> comments
    //     */
    //    public function getComments(): Collection
    //    {
    //        return $this->comments;
    //    }
    //
    //    /**
    //     * Add Comment function.
    //     *
    //     * @param Comment $comment comment
    //     *
    //     * @return $this this
    //     */
    //    public function addComment(Comment $comment): static
    //    {
    //        if (!$this->comments->contains($comment)) {
    //            $this->comments->add($comment);
    //            $comment->setPost($this);
    //        }
    //
    //        return $this;
    //    }

    /**
     * Remove Comment function.
     *
     * @param Comment $comment comment
     *
     * @return $this
     */
    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * Getter for Author.
     *
     * @return User|null author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for Author.
     *
     * @param User|null $author Author
     *
     * @return $this
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Getter for tags.
     *
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add Tag.
     *
     * @param Tag $tag Tag entity
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    /**
     * Remove tag.
     *
     * @param Tag $tag Tag entity
     *
     * @return Post post
     */
    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
