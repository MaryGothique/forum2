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
        // Controlla se l'utente esiste
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
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('home');
    }
}

