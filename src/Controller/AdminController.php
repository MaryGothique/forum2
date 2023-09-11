<?php

namespace App\Controller;

use App\Entity\Article;
use App\Controller\ArticleController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article')]
class AdminController extends AbstractController
{
    #[Route('/article/create', name: 'admin.article.create')]
    public function createArticle(Request $request)
    {
        return $this->render('article/index.html.twig');
    }
}
