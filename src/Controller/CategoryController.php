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
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $em
    ) {    
    }
        // create
    #[Route('/admin/category/create', name: 'admin.category.create')]
    #[IsGranted('ROLE_USER')]
    public function createCategory(Request $request): Response|RedirectResponse
    {
        $category = new Category();

        

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $this->em->persist($category);
            $this->em->flush();
            $this->addFlash('success', 'Category created');
            return $this->redirectToRoute('admin.category.read');
        }
       
        return $this->render('Backend/category/create.html.twig',[
            'form' => $form->createView()
        ]);

    }
    // CRUD ->read 
    #[Route('/admin/category/read', name:'admin.category.read')]
    public function readCategory():Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('Backend/category/index.html.twig', [
            'categories' => $categories,
            'current_user' => $this->getUser() // Passa l'utente corrente al template Twig
        ]);
    }
// edit update
#[Route('/admin/category/edit/{id}', name: 'admin.category.edit', methods: ['GET', 'POST'])]
#[IsGranted('ROLE_USER')]
public function edit(Category $category, Request $request): Response|RedirectResponse

{

    // Check if the user is logged in
    if (!$this->getUser()) {
        throw new AccessDeniedException('You must be logged in to edit category.');
    }
 
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->em->persist($category);
        $this->em->flush();

        $this->addFlash('success', 'Category modified successfully');
        return $this->redirectToRoute('admin.category.read');
    }

    return $this->render('Backend/category/edit.html.twig', [
        'form' => $form->createView()
    ]);
    $form = $this -> createForm(CategoryType::class, $category);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid())
    {
        $this->em->persist($category);
        $this->em->flush();

        $this->addFlash('success', 'category modified successifully');
       
        return $this->redirectToRoute('admin.category.read');
    }
    return $this->render('Backend/category/edit.html.twig', [
        'form' => $form->createView()
        ]);
}
// Delete 
#[Route('/admin/category/delete/{id}', name: 'admin.category.delete', methods:['POST', 'DELETE'])]
#[IsGranted('ROLE_USER')]
public function deleteCategory(?Category $category, Request $request): RedirectResponse
{ 
    
    // Controllo se l'utente è autenticato
   
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
