<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User; // Assicurati di importare la classe User se non è già importata

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    // Primary key ID, auto-generated by the database
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Title of the article
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 60,
        minMessage: 'Your title must be at least {{limit}} character long',
        maxMessage: 'Your title cannot be longer than {{ limit }} characters',
    )]
    private ?string $title = null;

    // Content of the article
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    // Creation timestamp of the article
    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $CreatedAt = null;

    // User who authored the article
    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // Categories associated with the article
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'articles')]
    private Collection $category;

    public function __construct()
    {
        // Initialize collections
        $this->category = new ArrayCollection();
    }

    // Getters and setters for id, title, content, createdAt, user, and category properties
    // Get the ID of the article
    public function getId(): ?int
    {
        return $this->id;
    }

    // Get the title of the article
    public function getTitle(): ?string
    {
        return $this->title;
    }

    // Set the title of the article
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    // Get the content of the article
    public function getContent(): ?string
    {
        return $this->content;
    }

    // Set the content of the article
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    // Get the creation timestamp of the article
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    // Set the creation timestamp of the article
    public function setCreatedAt(\DateTimeImmutable $CreatedAt): static
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    // Get the user who authored the article
    public function getUser(): ?User
    {
        return $this->user;
    }

    // Set the user who authored the article
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    // Get the categories associated with the article
    public function getCategory(): Collection
    {
        return $this->category;
    }

    // Get the categories associated with the article (alias for getCategory)
    public function getCategories(): Collection
    {
        return $this->category;
    }

    // Add a category to the article
    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    // Remove a category from the article
    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }
}
