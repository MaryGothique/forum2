<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConcertsController extends AbstractController
{
    // Route for displaying the concerts page
    #[Route('/concerts', name: 'concerts')]
    public function index(): Response
    {
        // Render the concerts index view and pass the 'concerts' variable to the template
        return $this->render('concerts/index.html.twig', [
            'concerts' => 'Concerts',
        ]);
    }
}

