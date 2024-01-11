<?php

namespace App\Controller\Admin\Category;
use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/admin/category/list', name: 'admin.category.index', methods: ['GET', 'POST'])]
    public function index(CategoryRepository $categoryRepository): Response
    {   
        $categories =$categoryRepository->findAll();
        return $this->render('pages/admin/category/index.html.twig',[
            'categories' => $categories
        ]);
    }

    #[Route('/admin/category/create', name: 'admin.category.create', methods: ['GET' ,'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {   

        $category = new Category();
       $form = $this->createForm(CategoryFormType ::class, $category);

       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) 
       {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'la catégorie a été bien enregistrée');

           return  $this->redirectToRoute('admin.category.index');
       }

        return $this->render('pages/admin/category/create.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/admin/category/{id}/edit', name: 'admin.category.edit', methods: ['GET' ,'PUT'])]
    public function edit(Category $category, Request $request, EntityManagerInterface $em) : Response
    {   
        $form = $this->createForm(CategoryFormType::class, $category, ['method' => 'PUT']);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())

        {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'la catégorie a bien  été modifiée');
            return $this->redirectToRoute('admin.category.index');


        }
        return $this->render("pages/admin/category/edit.html.twig",["form" => $form->createView()]);
    }

    #[Route('/admin/category/{id}/delete', name: 'admin.category.delete', methods: ['DELETE'])]
    public function delete(Category $category, Request $request, EntityManagerInterface $em ): Response 
    {
        if ($this->isCsrfTokenValid('delete_category'.$category->getId(), $request->request->get('csrf_token')))
        {
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'Cette  catégorie a bien été supprimée');
        }
    
        return $this->redirectToRoute('admin.category.index');
    }
} 
