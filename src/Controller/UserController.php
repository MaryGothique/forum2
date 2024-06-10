<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function profile(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/edit/{id}', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $entityManager->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['POST', 'DELETE'])]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): Response
    {
        // Delete all categories created by the user
        $categories = $entityManager->getRepository(Category::class)->findBy(['createdBy' => $user]);
        foreach ($categories as $category) {
            $entityManager->remove($category);
        }

        // Delete all articles created by the user
        $articles = $user->getArticles();
        foreach ($articles as $article) {
            // Delete all categories associated with the article
            $articleCategories = $article->getCategories();
            foreach ($articleCategories as $category) {
                $entityManager->remove($category);
            }

            // Delete all comments associated with the article
            $comments = $article->getComments();
            foreach ($comments as $comment) {
                $entityManager->remove($comment);
            }

            $entityManager->remove($article);
        }

        // Delete all comments made by the user
        $userComments = $entityManager->getRepository(Comment::class)->findBy(['user' => $user]);
        foreach ($userComments as $comment) {
            $entityManager->remove($comment);
        }

        // Finally, delete the user
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_logout');
    }
}
