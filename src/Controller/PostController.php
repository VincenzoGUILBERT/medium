<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(Request $request, PostRepository $postRepository, TagRepository $tagRepository): Response
    {
        $tag = null;

        if ($request->query->has('tag')) {
            $tag = $tagRepository->findOneBy(['name' => $request->query->get('tag')]);
        }
        
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAllWithJoin($tag),
        ]);
    }
    
    #[Route('/post/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }
    
    #[Route('/post/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(PostRepository $postRepository, $id): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $postRepository->findWithJoin($id)
        ]);
    }
    
    #[Route('/post/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }
    
    #[Route('/post/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/tag', name: 'app_tag_index', methods: ['GET'])]
    public function tagsIndex(TagRepository $tagRepository): Response
    {
        return $this->render('/post/tag_index.html.twig', [
            'tags' => $tagRepository->findAll()
        ]);
    }
}