<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    // Defines a route for displaying the user's profile
    #[Route('/user', name: 'user')]
    public function profile(): Response
    {
        // Gets the currently logged-in user
        $user = $this->getUser();

        // Checks if the user exists
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        // Renders the user's profile view
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    // Defines a route for editing user information
    #[Route('/user/edit/{id}', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Checks if the user exists
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        // Creates the form for editing the user
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        // Processes the form submission
        if ($form->isSubmitted() && $form->isValid()) {
            // If a new password is provided, hash it and set it on the user
            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            // Saves the changes to the database
            $entityManager->flush();

            // Redirects to the user profile page
            return $this->redirectToRoute('user');
        }

        // Renders the form view for editing the user
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    // Defines a route for deleting a user
    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['POST', 'DELETE'])]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): Response
    {
        
 
         //delete every article made by the user 
       
        /**
         * Retrieving user's articles: Utilizing the getArticles() method of the user object,
         *  we obtain a collection of all articles created by that user. 
         * This collection represents a relationship between the user entity 
         * and the article entity defined within your Symfony application.
         */
        $articles = $user->getArticles();
        /**
         * Iterating over the articles: For each article found,
         *  we loop through every article in the retrieved collection. 
         * This is done using a foreach loop that iterates over each
         *  article in the collection.
         */
        foreach ($articles as $article) {
            /**
             * Removing the articles: For each article encountered, 
             * we call the remove() method on the $entityManager object (EntityManagerInterface)
             *  to mark the article for removal from the database.
             */
            $entityManager->remove($article);
        }
        /**
         * Flushing the changes: Once all articles are marked for removal,
         *  the changes are actually applied by calling the flush() method on the $entityManager object.
         * This triggers the actual deletion of the articles from the database.
         */

        // delete every category made by the user 
        foreach ($articles as $article) {
            $categories = $article->getCategories();
            foreach ($categories as $category) {
                $entityManager->remove($category);
            }
        }

        // delete user
        $entityManager->remove($user);
    
        // execute the flush in the database
        $entityManager->flush();
    
        // Redirect on the homepage
        return $this->redirectToRoute('home');
    }
}


