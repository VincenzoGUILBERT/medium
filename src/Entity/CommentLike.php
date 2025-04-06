<?php

namespace App\Entity;

use App\Entity\LikeBase;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentLikeRepository;

#[ORM\Entity(repositoryClass: CommentLikeRepository::class)]
class CommentLike extends LikeBase
{
    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comment $comment = null;
    
    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
