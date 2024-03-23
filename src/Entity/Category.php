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

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'category')]
    private Collection $articles;

    private $user;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $author = null;

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Article>
     */
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

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            $article->removeCategory($this);
        }

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;

        return $this;
    }
}
