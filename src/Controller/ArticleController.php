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
    public function __construct(
        private ArticleRepository $articleRepository,
        private EntityManagerInterface $em,
        private CommentRepository $repoComment
    )
    {   
    }

    #[Route('/article/{id}', name: 'article_detail')]
    public function articleDetail(Article $article, Request $request): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setArticle($article);
            $comment->setUser($this->getUser());
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setUpdatedAt(new \DateTimeImmutable());

            $this->em->persist($comment);
            $this->em->flush();

            $this->addFlash('success', 'Comment added successfully!');
            return $this->redirectToRoute('article_detail', ['id' => $article->getId()]);
        }

        return $this->render('Backend/article/_detail.html.twig', [
            'article' => $article,
            'commentForm' => $commentForm->createView(),
        ]);
    }

    /**
     * This is the CRUD: Create, Read, Update, and Delete. Here starts the Create.
     */
    #[Route('/admin/article/create', name: 'admin.article.create')]
    #[IsGranted('ROLE_USER')]
    public function createArticle(Request $request, CategoryRepository $categoryRepo): Response|RedirectResponse
    {
        //Here is meaning that every new article created search every article and every category
        $article = new Article();
        $categories = $categoryRepo->findAll();
        //ArticleType is the form 
        $form = $this->createForm(ArticleType::class, $article);       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUser($this->getUser());

            //With this code we will send it in the database
            $this->em->persist($article);
            $this->em->flush();
            //the flash messages are the messages that appears when you do an action
            //there are: successful flash message, danger flash message, info... 
            $this->addFlash('success', 'Article created');
            return $this->redirectToRoute('admin.article.read');
        }

        return $this->render('Backend/article/create.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }

    /**
     * Here starts the Read of the CRUD.
     */
    #[Route('/admin/article/read', name: 'admin.article.read')]
    public function readArticle(): Response
    {
        // Here is take every article for see it 
        $articles = $this->articleRepository->findAll();

        return $this->render('Backend/article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * Here we can find the Edit (Update).
     */
    #[Route('/admin/article/edit/{id}', name: 'admin.article.edit', methods: ['GET', 'POST'])]
    public function edit(Article $article, Request $request): Response|RedirectResponse
    {
        //if user is logged, if he's not logged, the message with 'accessDeniedException'
        if (!$this->getUser()) {
            throw new AccessDeniedException('You must be logged in to edit articles.');
        }
        // if the user is not the author, addFlash message
        if ($article->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'You are not allowed to edit this article.');
            return $this->redirectToRoute('admin.article.read');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($article);
            $this->em->flush();
            $this->addFlash('success', 'Article modified successfully!');     
            return $this->redirectToRoute('admin.article.read');
        }

        return $this->render('Backend/article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Here there is the Delete.
     */
    #[Route('/admin/article/delete/{id}', name: 'admin.article.delete', methods: ['POST', 'DELETE'])]
    public function deleteArticle(?Article $article, Request $request): RedirectResponse
    {
        // Controllo se l'utente è autenticato
        if (!$this->getUser()) {
            throw new AccessDeniedException('You must be logged in to delete articles.');
        }
        if (!$article instanceof Article) {
            $this->addFlash('error', 'Article not found');
            return $this->redirectToRoute('admin.article.read');
        }
        if ($article->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'You are not allowed to delete this article.');
            return $this->redirectToRoute('admin.article.read');
        }

        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('token'))) {
            $this->em->remove($article);
            $this->em->flush();

            $this->addFlash('success', 'Article deleted successfully');
            return $this->redirectToRoute('admin.article.read');
        }

        $this->addFlash('error', 'Invalid token');
        return $this->redirectToRoute('admin.article.read');
    }
}

