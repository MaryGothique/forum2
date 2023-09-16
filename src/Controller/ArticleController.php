<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    

    #[Route('/admin/article/create', name: 'admin.article.create')]
    public function createArticle(EntityManagerInterface $em, Request $request): Response|RedirectResponse
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             
            // Formulaire soumis est valide
            // $form->getData();  holds the submitted values
            // but, the original `$task` variable has also been updated
           
            //ici, aujoute l'auteur à l'article

            
             $article->setUser($this->getUser());

            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('admin.article.create');

        }
        return $this->render('article/index.html.twig', [
            'form' => $form->createView()
            ]);
    }
    #[Route('/admin/article/read', name:'admin.article.read')]
    public function readArticle():Response
    {
        return $this->render('article/create.html.twig');
    }
    
}
