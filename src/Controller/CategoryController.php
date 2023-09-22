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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $em
    ) {    
    }

    #[Route('/admin/category/create', name: 'admin.category.create')]
    public function createCategory(Request $request): Response|RedirectResponse
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $this->em->persist($category);
            $this->em->flush();

            return $this->redirectToRoute('admin.category.read');
        }
        return $this->render('Backend/category/create.html.twig',[
            'form' => $form->createView()
        ]);

    }
    // CRUD de la Read
    #[Route('/admin/category/read', name:'admin.category.read')]
    public function readCategory():Response
    {
        $categories = $this ->categoryRepository->findAll();

        return $this->render('Backend/category/index.html.twig',[
            'categories' => $categories,
        ]);
    }
//CRUD pour le EDIT
#[Route('/admin/category/edit/{id}', name: 'admin.category.edit', methods: ['GET', 'POST'])]
public function edit(Category $category, Request $request): Response|RedirectResponse

{
    $form = $this -> createForm(CategoryType::class, $category);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid())
    {
        $this->em->persist($category);
        $this->em->flush();

        $this->addFlash('success', 'category modifié avec succès');

        return $this->redirectToRoute('admin.category.read');
    }
    return $this->render('Backend/category/edit.html.twig', [
        'form' => $form->createView()
        ]);
}

//crud du delete
#[Route('/admin/category/delete/{id}', name: 'admin.category.delete', methods:['POST', 'DELETE'])]
public function deleteCategory(?Category $category, Request $request): RedirectResponse
{
    if (!$category instanceof Category) {
        $this->addFlash('error',  'category not found');

        return $this->redirectToRoute('admin.category.read');
    }
    if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('token'))) {
        $this->em->remove($category, true);
        $this->em->flush();

        $this->addFlash('success', 'category deleted successfully');

        return $this->redirectToRoute('admin.category.read');
    }
}
}
