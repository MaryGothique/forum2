<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    // Route for the user profile page
    #[Route('/user', name: 'user')]
    public function profile(): Response
    {
        // Get the current user
        $user = $this->getUser();

        // Check if the user is authenticated
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        // Pass the user data to the Twig template
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
