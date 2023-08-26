<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; // <- va permettre de pouvoir récupérerla reponse de la requete
use Symfony\Component\Routing\Annotation\Route; // <- va permettre de définir lesroutes pour les functions
class FrontController extends AbstractController
{
#[Route('/', name: 'home')]
public function index(): Response
{
    return $this->render('./home.html.twig');
}

}
