<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
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
        private EntityManagerInterface $em
    )
    {
        
    }
   /**
    * This is the crud: CRUD is for Create, Read, Update and Delete. Here starts the Create
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
            //
            // $form->getData();  holds the submitted values
            // but, the original `$task` variable has also been updated
     
             $article->setUser($this->getUser());
           
            //With this code we will send it in the database
            $this->em->persist($article);
            $this->em->flush();
            //the flash messages are the messages that appears when you do an action
            //there are: successful flash message, danger flash message, info... 
            $this->addFlash('success', 'Article created');
                $this->em->flush();
                return $this->redirectToRoute('admin.article.read');

        }
        return $this->render('Backend/article/create.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
            ]);
    } 
    /**
     * Here starts the Read of the CRUD
     */
    #[Route('/admin/article/read', name:'admin.article.read')]
    public function readArticle():Response
    {
        // Here is take every article for see it 
        $articles = $this->articleRepository->findAll();

        return $this->render('Backend/article/index.html.twig',[
            'articles' => $articles,
           
        ]);
    
    }
    /**
     * Here we can find the Edit.
     */
    #[Route('/admin/article/edit/{id}', name: 'admin.article.edit', methods: 'GET|POST')]

    public function edit(Article $article, Request $request): Response|RedirectResponse
    {
/*
        //faire des vardump pour verifier des valeurs des variables sur le navigateur
            var_dump($article);
        // trouver l id de l utilisateur 
        var_dump($this->getUser());
        var_dump($this->getUser()->getId());
       
        //trouve l id de l article sur laquelle l user a cliqué 
            var_dump($article->getId());
*/
        //if user are logged, if he's not logged  the message with 'accessDeniedException'
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
    
            $this->addFlash('success', 'Article modified with success!');
            
            return $this->redirectToRoute('admin.article.read');
        }
    
        return $this->render('Backend/article/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * Here there are the Delete
     */
    #[Route('/admin/article/delete/{id}', name:'admin.article.delete', methods: ['POST', 'DELETE'])]
   
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
                $this->em->remove($article, true);
                $this->em->flush();
                        
                $this->addFlash('success', 'Article deleted successfully');
                return $this->redirectToRoute('admin.article.read');
            }

            $this->addFlash('error', 'Invalid token');

            return $this->redirectToRoute('admin.article.read');
        }
        /**
         * this is the page of the single article
         */
        #[Route('/article/{id}', name: 'article_detail')]
        public function articleDetail(Article $article): Response
        {
            return $this->render('Backend/article/_detail.html.twig', [
                'article' => $article
            ]);
}
}
