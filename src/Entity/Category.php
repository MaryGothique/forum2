<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'categories')]
    private Collection $articles;
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserInterface $user = null;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }
    

    // Get the ID of the category
    public function getId(): ?int
    {
        return $this->id;
    }

    // Get the title of the category
    public function getTitle(): ?string
    {
        return $this->title;
    }

    // Set the title of the category
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    // Get the articles associated with the category

    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->addCategory($this);
        }

        return $this;
    }

    // Remove an article from the category
    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            $article->removeCategory($this);
        }

        return $this;
    }

    // Get the user who created the category
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    // Set the user who created the category
    public function setUser(?UserInterface $user): static
    {
        $this->user = $user;
        return $this;
    }


}



