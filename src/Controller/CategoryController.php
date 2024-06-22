<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/user/category/create', name: 'user.category.create')]
    #[IsGranted('ROLE_USER')]
    public function createCategory(Request $request): Response|RedirectResponse
    {
        $category = new Category();
        $category->setUser($this->getUser());

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($category);
            $this->em->flush();

            $this->addFlash('success', 'Category created');
            return $this->redirectToRoute('user.category.read');
        }

        return $this->render('Backend/category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/category/read', name: 'user.category.read')]
    public function readCategory(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('Backend/category/index.html.twig', [
            'categories' => $categories,
            'current_user' => $this->getUser()
        ]);
    }

    #[Route('/user/category/edit/{id}', name: 'user.category.edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Category $category, Request $request): Response|RedirectResponse
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'You must be logged in to edit category.');
            return $this->redirectToRoute('user.category.read');
        }

        if ($category->getUser() !== $user) {
            $this->addFlash('error', 'You do not have permission to edit this category.');
            return $this->redirectToRoute('user.category.read');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($category);
            $this->em->flush();

            $this->addFlash('success', 'Category modified successfully');
            return $this->redirectToRoute('user.category.read');
        }

        return $this->render('Backend/category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/category/delete/{id}', name: 'user.category.delete', methods: ['POST', 'DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteCategory(?Category $category, Request $request): RedirectResponse
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'You must be logged in to delete category.');
            return $this->redirectToRoute('user.category.read');
        }

        if (!$category instanceof Category) {
            $this->addFlash('error', 'Category not found');
            return $this->redirectToRoute('user.category.read');
        }

        if ($category->getUser() !== $user) {
            $this->addFlash('error', 'You do not have permission to delete this category.');
            return $this->redirectToRoute('user.category.read');
        }

        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('token'))) {
            $this->em->remove($category);
            $this->em->flush();

            $this->addFlash('success', 'Category deleted successfully');
            return $this->redirectToRoute('user.category.read');
        }

        $this->addFlash('error', 'Invalid token');
        return $this->redirectToRoute('user.category.read');
    }
}
