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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CategoryController extends AbstractController
{
    // Dependency injection of the CategoryRepository and EntityManager
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $em
    ) {    
    }

    // Route for creating a new category
    #[Route('/admin/category/create', name: 'admin.category.create')]
    #[IsGranted('ROLE_USER')]
    public function createCategory(Request $request): Response|RedirectResponse
    {
        $category = new Category();

        // Create and handle the form for the new category
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Persist and flush the category to the database
            $this->em->persist($category);
            $this->em->flush();

            // Add a success flash message and redirect to the category list page
            $this->addFlash('success', 'Category created');
            return $this->redirectToRoute('admin.category.read');
        }
       
        // Render the category creation form view
        return $this->render('Backend/category/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

    // Route for reading (listing) categories
    #[Route('/admin/category/read', name:'admin.category.read')]
    public function readCategory(): Response
    {
        // Retrieve all categories from the repository
        $categories = $this->categoryRepository->findAll();

        // Render the category list view and pass the current user to the template
        return $this->render('Backend/category/index.html.twig', [
            'categories' => $categories,
            'current_user' => $this->getUser()
        ]);
    }

    // Route for editing an existing category
    #[Route('/admin/category/edit/{id}', name: 'admin.category.edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Category $category, Request $request): Response|RedirectResponse
    {
        // Ensure the user is logged in
        if (!$this->getUser()) {
            throw new AccessDeniedException('You must be logged in to edit category.');
        }

        // Create and handle the form for editing the category
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist and flush the changes to the database
            $this->em->persist($category);
            $this->em->flush();

            // Add a success flash message and redirect to the category list page
            $this->addFlash('success', 'Category modified successfully');
            return $this->redirectToRoute('admin.category.read');
        }

        // Render the category edit form view
        return $this->render('Backend/category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Route for deleting a category
    #[Route('/admin/category/delete/{id}', name: 'admin.category.delete', methods:['POST', 'DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteCategory(?Category $category, Request $request): RedirectResponse
    { 
        // Ensure the category exists
        if (!$category instanceof Category) {
            $this->addFlash('error', 'Category not found');
            return $this->redirectToRoute('admin.category.read');
        }

        // Validate the CSRF token before deleting the category
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('token'))) {
            // Remove the category and flush the changes to the database
            $this->em->remove($category, true);
            $this->em->flush();

            // Add a success flash message and redirect to the category list page
            $this->addFlash('success', 'Category deleted successfully');
            return $this->redirectToRoute('admin.category.read');
        }

        // Add an error flash message for an invalid token and redirect to the category list page
        $this->addFlash('error', 'Invalid token');
        return $this->redirectToRoute('admin.category.read');
    }
}
