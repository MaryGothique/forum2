<?php
/**
 * This is a controller for the cookies page
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CookiesController extends AbstractController
{
    // Route for displaying the cookies page
    #[Route('/cookies', name: 'cookies')]
    public function index(): Response
    {
        // Render the cookies index view and pass the 'cookies' variable to the template
        return $this->render('cookies/index.html.twig', [
            'cookies' => 'cookies',
        ]);
    }
}

