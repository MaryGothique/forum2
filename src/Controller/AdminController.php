<?php

namespace App\Controller;

use App\Entity\Article;
use App\Controller\AdminController;
use App\Controller\ArticleController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/article/create', name: 'admin.article.create')]
    public function createArticle(Request $request)
    {
    $article = new Article();
    $form = $this->createForm(ArticleType::class, $article);
    return $this->render('article/index.html.twig', [
    'form' => $form->createView()
    ]);
}
    #[Route('/admin')]
        class AdminController extends ArticleController
        {
            
        }
    #[Route('/article/create', name: 'admin.article.create')]
    public function createArticle(Request $request)
    {
    return $this->render('./article/index.html.twig');
    }
}