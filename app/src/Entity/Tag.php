<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Category;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag
 *
 */
#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'tags')]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $title = null;

//    /**
//     * Author.
//     *
//     * @var User|null
//     */
//    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
//    #[ORM\JoinColumn(nullable: false)]
//    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

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
