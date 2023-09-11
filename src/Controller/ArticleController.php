<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'admin.article.create')]
    public function createArticle(Request $request, ArticleRepository $articleRepository) 
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);       

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $article = $form->getData();

            // ... perform some action, such as saving the task to the database
            $article = new Article();
            $article->setTitle('Titre')
                ->setContent('Content')
                ->setAuthor('Author'); 
            return $this->redirectToRoute('home');
        }

        return $this->render('article/index.html.twig', [
            'form' => $form,
        ]);
    }
    public function showArticle(int $id, ArticleRepository $articleRepository)
{
    $article = $articleRepository->find($id);
    // ...
}
}
