<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Primary key ID, auto-generated by the database
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Email address of the user
    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
    private ?string $email = null;

    // Roles assigned to the user
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\PasswordStrength(['minScore' => PasswordStrength::STRENGTH_MEDIUM])]
    private ?string $password = null;

    // Articles associated with the user
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Article::class)]
    private Collection $articles;

    // Nickname of the user
    #[ORM\Column(length: 30)]
    private ?string $nickname = null;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Comment::class)]
    private Collection $Article;

    // Constructor to initialize collections
    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->Article = new ArrayCollection();
    }

    // Get the ID of the user
    public function getId(): ?int
    {
        return $this->id;
    }

    // Get the email address of the user
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // Set the email address of the user
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    // Get a unique identifier for the user
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // Get the roles assigned to the user
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    // Set the roles assigned to the user
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    // Get the hashed password of the user
    public function getPassword(): string
    {
        return $this->password;
    }

    // Set the hashed password of the user
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    // Erase any sensitive data stored by the user
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    // Get the articles associated with the user
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    // Add an article to the user
    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setUser($this);
        }

        return $this;
    }

    // Remove an article from the user
    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    // Get the nickname of the user
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    // Set the nickname of the user
    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getArticle(): Collection
    {
        return $this->Article;
    }

}
