<?php

/**
 * @license <licencja>
 */

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 */
#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'tags')]
class Tag
{
    /**
     * Id.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Title.
     */
    #[ORM\Column(length: 64)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private ?string $title = null;

    //    /**
    //     * Author.
    //     *
    //     * @var User|null
    //     */
    //    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    //    #[ORM\JoinColumn(nullable: false)]
    //    private ?User $author = null;

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
     * Getter for Title.
     *
     * @return string|null title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for Title.
     *
     * @param string $title title
     *
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    //    public function getAuthor(): ?User
    //    {
    //        return $this->author;
    //    }
    //
    //    public function setAuthor(?User $author): static
    //    {
    //        $this->author = $author;
    //
    //        return $this;
    //    }
}
