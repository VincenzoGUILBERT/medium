<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\PostLike;
use App\Entity\CommentLike;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LikeController extends AbstractController
{
    #[Route('/like/post/{id}', name: 'post_like', methods: ['POST'])]
    public function likePost(Post $post, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'Unauthorized, please sign in to put a like'], 403);
        }

        // Check if like already exist
        $existingLike = $entityManager->getRepository(PostLike::class)->findOneBy([
            'user' => $user,
            'post' => $post
        ]);

        if ($existingLike) {
            $entityManager->remove($existingLike);
            $entityManager->flush();
            return new JsonResponse(['message' => 'Like removed'], 204);
        }

        // Add a like
        $like = new PostLike();
        $like->setUser($user);
        $like->setPost($post);
        $entityManager->persist($like);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Post liked'], 201);
    }

    #[Route('/like/comment/{id}', name: 'comment_like', methods: ['POST'])]
    public function likeComment(Comment $comment, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'Unauthorized, please sign in to put a like'], 403);
        }

        // Check if like already exist
        $existingLike = $entityManager->getRepository(CommentLike::class)->findOneBy([
            'user' => $user,
            'comment' => $comment
        ]);

        if ($existingLike) {
            $entityManager->remove($existingLike);
            $entityManager->flush();
            return new JsonResponse(['message' => 'Like removed'], 204);
        }

        $like = new CommentLike();
        $like->setUser($user);
        $like->setComment($comment);
        $entityManager->persist($like);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Comment liked'], 201);
    }
}