<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; // <- will allow to get the response of the request
use Symfony\Component\Routing\Annotation\Route; // <- will allow to define the routes for the functions

class FrontController extends AbstractController
{
    // Route for the home page
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Render the home page template
        return $this->render('./home.html.twig');
    }
}
