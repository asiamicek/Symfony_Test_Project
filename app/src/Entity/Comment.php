<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
class Comment
{
    /**
     * Id.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Content.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $content = null;

    /**
     * Created at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Post.
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    /**
     * Author.
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    private ?User $author;

    /**
     * Getter for id.
     *
     * @return int|null id
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return \DateTimeImmutable|null Date time
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param \DateTimeImmutable $createdAt Date time
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for Post.
     *
     * @return Post|null post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * Setter for Post.
     *
     * @param Post|null $post post
     *
     * @return $this
     */
    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Setter for author.
     *
     * @param User|null $author author
     *
     * @return $this this
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

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
}
