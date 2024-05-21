<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Route for the login page
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // Get the last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        // Render the login template and pass the last username and error to the template
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    
    // Route for logging out
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout()
    {
        // This method can be blank - it will be intercepted by the logout key on your firewall.
        // This is a placeholder to avoid logic exceptions.
        // Render the home page template after logging out
        return $this->render('home.html.twig');
    }
}
