<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ArticleController extends AbstractController
{
    // Dependency injection of the ArticleRepository and EntityManager
    public function __construct(
        private ArticleRepository $articleRepository,
        private EntityManagerInterface $em,
        private CommentRepository $commentRepository
    )
    {   
    }

    // Route for displaying the details of an article
    #[Route('/article/{id}', name: 'article_detail')]
public function articleDetail(Article $article, Request $request, CommentRepository $commentRepository): Response
{
    // creating form comment
    $comment = new Comment();
    $commentForm = $this->createForm(CommentType::class, $comment);
    $commentForm->handleRequest($request);

    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        $comment->setUser($this->getUser());
        $comment->setArticle($article);
        $comment->setCreatedAt(new \DateTime());
        
        $this->em->persist($comment);
        $this->em->flush();

        return $this->redirectToRoute('article_detail', ['id' => $article->getId()]);
    }

    //render the template with the datas
    return $this->render('Backend/article/_detail.html.twig', [
        'article' => $article,
        'commentForm' => $commentForm->createView(),
    ]);
    }

    /**
     * Route for creating a new article
     */
    #[Route('/user/article/create', name: 'user.article.create')]
    #[IsGranted('ROLE_USER')]
    public function createArticle(Request $request): Response|RedirectResponse
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTimeImmutable());
            $article->setUser($this->getUser());
            
            $this->em->persist($article);
            $this->em->flush();

            $this->addFlash('success', 'Article created');
            return $this->redirectToRoute('user.article.read');
        }

        return $this->render('Backend/article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * Route for reading (listing) articles
     */
    #[Route('/user/article/read', name: 'user.article.read')]
    public function readArticle(): Response
    {
        // Retrieve all articles from the repository
        $articles = $this->articleRepository->findAll();

        // Render the article list view
        return $this->render('Backend/article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * Route for editing an article
     */
    #[Route('/user/article/edit/{id}', name: 'user.article.edit', methods: ['GET', 'POST'])]
    public function edit(Article $article, Request $request): Response|RedirectResponse
    {
        // Ensure the user is logged in
        if (!$this->getUser()) {
            throw new AccessDeniedException('You must be logged in to edit articles.');
        }

        // Ensure the logged-in user is the author of the article
        if ($article->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'You are not allowed to edit this article.');
            return $this->redirectToRoute('user.article.read');
        }

        // Create and handle the form for editing the article
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes and flush to the database
            $this->em->persist($article);
            $this->em->flush();
            $this->addFlash('success', 'Article modified successfully!');     
            return $this->redirectToRoute('user.article.read');
        }

        // Render the article edit form view
        return $this->render('Backend/article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Route for deleting an article
     */
    #[Route('/user/article/delete/{id}', name: 'user.article.delete', methods: ['POST', 'DELETE'])]
    public function deleteArticle(?Article $article, Request $request): RedirectResponse
    {
        // Ensure the user is logged in
        if (!$this->getUser()) {
            throw new AccessDeniedException('You must be logged in to delete articles.');
        }

        // Ensure the article exists
        if (!$article instanceof Article) {
            $this->addFlash('error', 'Article not found');
            return $this->redirectToRoute('user.article.read');
        }

        // Ensure the logged-in user is the author of the article
        if ($article->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'You are not allowed to delete this article.');
            return $this->redirectToRoute('user.article.read');
        }

        // Validate the CSRF token before deleting the article
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('token'))) {
            // Remove the article and flush the changes to the database
            $this->em->remove($article);
            $this->em->flush();

            // Add a success flash message and redirect to the article list page
            $this->addFlash('success', 'Article deleted successfully');
            return $this->redirectToRoute('user.article.read');
        }

        // Add an error flash message for an invalid token and redirect to the article list page
        $this->addFlash('error', 'Invalid token');
        return $this->redirectToRoute('user.article.read');
    }
}
