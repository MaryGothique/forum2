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
    // Constructor to initialize the CategoryRepository and EntityManagerInterface
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $em
    ) {    
    }

    // Route to create a new category
    #[Route('/user/category/create', name: 'user.category.create')]
    #[IsGranted('ROLE_USER')] // Ensure the user has ROLE_USER
    public function createCategory(Request $request): Response|RedirectResponse
    {
        $category = new Category();
        $category->setCreatedBy($this->getUser()); // Set the creator of the category

        // Create the form for CategoryType and handle the request
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($category); // Persist the new category
            $this->em->flush(); // Flush the changes to the database

            $this->addFlash('success', 'Category created'); // Add a success message
            return $this->redirectToRoute('user.category.read'); // Redirect to category read route
        }

        // Render the category creation form
        return $this->render('Backend/category/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

    // Route to read all categories
    #[Route('/user/category/read', name:'user.category.read')]
    public function readCategory(): Response
    {
        // Retrieve all categories from the repository
        $categories = $this->categoryRepository->findAll();

        // Render the categories in the template
        return $this->render('Backend/category/index.html.twig', [
            'categories' => $categories,
            'current_user' => $this->getUser() // Pass the current user to the template
        ]);
    }

    // Route to edit a category
    #[Route('/user/category/edit/{id}', name: 'user.category.edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')] // Ensure the user has ROLE_USER
    public function edit(Category $category, Request $request): Response|RedirectResponse
    {
        $user = $this->getUser();

        // Ensure the user is logged in
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to edit category.');
            return $this->redirectToRoute('user.category.read');
        }

        // Ensure the user has permission to edit the category
        if ($category->getCreatedBy() !== $user) {
            $this->addFlash('error', 'You do not have permission to edit this category.');
            return $this->redirectToRoute('user.category.read');
        }

        // Create the form for CategoryType and handle the request
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($category); // Persist the changes to the category
            $this->em->flush(); // Flush the changes to the database

            $this->addFlash('success', 'Category modified successfully'); // Add a success message
            return $this->redirectToRoute('user.category.read'); // Redirect to category read route
        }

        // Render the category edit form
        return $this->render('Backend/category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Route to delete a category
    #[Route('/user/category/delete/{id}', name: 'user.category.delete', methods:['POST', 'DELETE'])]
    #[IsGranted('ROLE_USER')] // Ensure the user has ROLE_USER
    public function deleteCategory(?Category $category, Request $request): RedirectResponse
    { 
        $user = $this->getUser();

        // Ensure the user is logged in
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to delete category.');
            return $this->redirectToRoute('user.category.read');
        }

        // Ensure the category exists
        if (!$category instanceof Category) {
            $this->addFlash('error', 'Category not found');
            return $this->redirectToRoute('user.category.read');
        }

        // Ensure the user has permission to delete the category
        if ($category->getCreatedBy() !== $user) {
            //CreatedBy is a function that give me the permission to delete or modify the category
            $this->addFlash('error', 'You do not have permission to delete this category.');
            return $this->redirectToRoute('user.category.read');
        }

        // Check if the CSRF token is valid
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('token'))) {
            $this->em->remove($category); // Remove the category
            $this->em->flush(); // Flush the changes to the database

            $this->addFlash('success', 'Category deleted successfully'); // Add a success message
            return $this->redirectToRoute('user.category.read'); // Redirect to category read route
        }

        $this->addFlash('error', 'Invalid token'); // Add an error message
        return $this->redirectToRoute('user.category.read'); // Redirect to category read route
    }
}
