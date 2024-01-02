<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/admin/post/list', name: 'admin.post.index', methods: ['GET'])]
    public function index(PostRepository $repository ): Response
    {
        $posts = $repository->findAll();
        return $this->render('pages/admin/post/index.html.twig', ["posts" => $posts ]);
    }

    #[Route('/admin/post/create', name: 'admin.post.create', methods: ['GET', 'POST'])]
    public function create(Request $request, 
    EntityManagerInterface $em, 
    CategoryRepository  $categoryRepository): Response 
    {   
        if ( count($categoryRepository->findAll())<= 0) 
        {
            $this->addFlash('warning', "Vous devez créer au moins une catégorie avant de rédiger un article.");
            return $this->redirectToRoute('admin.category.index');
        }

        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $admin = $this->getUser();
            $post->setUser($admin);

            $em->persist($post);
            $em->flush();
             $this->addFlash('success', 'Votre article a bien été créé.');
             return $this->redirectToRoute('admin.post.index');

        }
        return $this->render('pages/admin/post/create.html.twig', [
            "form" => $form->CreateView()  ]);
    }

    #[Route('/admin/post/{id}/publish', name: 'admin.post.publish', methods: ['PUT'])]
    public function publish(Post $post, Request $request, EntityManagerInterface $em):Response
    {
        if ($this->isCsrfTokenValid('publish_post'.$post->getId(), $request->request->get('csrf_token')))
        {
            if( false ===$post->isIsPublished())
            {
                $post->setIsPublished(true);

                $post->setPublishedAt(new \DateTimeImmutable());

                $this->addFlash('success', 'Votre article a bien été publié.');
            }

            else
            {
                $post->setIsPublished(false);
                $this->addFlash('success', 'Votre article a bien été dépublié.');

            }

            $em->persist($post);
            $em->flush();

        }
        return $this->redirectToRoute('admin.post.index');
    }

    #[Route('/admin/post/{id}/show', name: 'admin.post.show', methods: ['GET'])]
    public function show(Post $post): Response
    {

        return $this->render("pages/admin/post/show.html.twig", ["post" => $post]);
    }

    #[Route('/admin/post/{id}/edit', name: 'admin.post.edit', methods: ['GET', 'PUT'])]
    public function edit(Post $post, 
    Request $request, 
    EntityManagerInterface $em, 
    CategoryRepository  $categoryRepository): Response
    {   
        if ( count($categoryRepository->findAll())<= 0)
        {
            $this->addFlash('warning', "Vous devez créer au moins une catégorie avant de rédiger un article.");
            return $this->redirectToRoute('admin.category.index');
        }
        $form = $this->createForm(PostFormType::class, $post, [
            'method'=> 'PUT',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $post->setUser($this->getUser());
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Votre article a bien été modifié.');
            return $this->redirectToRoute('admin.post.index');
        }

       return  $this->render('pages/admin/post/edit.html.twig', 
        ["form" => $form->createView(),
         "post" => $post]);
    }

    #[Route('/admin/post/{id}/delete', name: 'admin.post.delete', methods: ['DELETE'])]
    public function delete(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_post_'.$post->getId(), $request->request->get('csrf_token')))
        {
            $em->remove($post);
            $em->flush();
            $this->addFlash('success', 'Cet article a bien été supprimé');
        }
    
        return $this->redirectToRoute('admin.post.index');
    }
}
