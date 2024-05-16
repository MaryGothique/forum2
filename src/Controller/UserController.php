<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function profile(): Response
    {
        // Ottieni l'utente corrente
        $user = $this->getUser();

        // Verifica se l'utente è autenticato
        if (!$user) {
            throw $this->createNotFoundException('Utente non trovato.');
        }

        // Passa i dati dell'utente al template Twig
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
