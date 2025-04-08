<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $user = $this->getUser();
        $follow = $request->query->getBoolean('follow');
        $page = $request->query->get('page', 1);

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAllUsers($page, $user, $follow),
        ]);
    }

    #[Route('/feed', name: 'app_post_feed', methods: ['GET'])]
    public function feed(PostRepository $postRepository): Response
    {
        $user = $this->getUser();

        return $this->render('/post/index.html.twig', [
            'posts' => $postRepository->findPostsFromFollowedUsers($user)
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, PostRepository $postRepository): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'posts' => $postRepository->findAllbyUser($user),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}