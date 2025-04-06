<?php

namespace App\Entity;

use App\Entity\LikeBase;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostLikeRepository;

#[ORM\Entity(repositoryClass: PostLikeRepository::class)]
class PostLike extends LikeBase
{
    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }
}
