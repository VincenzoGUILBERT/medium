<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllWithJoin($tag): array
    {
        $qb = $this->createQueryBuilder('p')
                ->addSelect('a', 't', 'c', 'l')
                ->innerJoin('p.author', 'a')
                ->leftJoin('p.comments', 'c')
                ->leftJoin('p.likes', 'l')
                ->leftJoin('p.tags', 't')
                ->orderBy('p.createdAt', 'DESC');

        if ($tag !== null) {
            $qb->andWhere(':tag MEMBER OF p.tags')
                ->setParameter('tag', $tag);
        }

        return $qb->getQuery()
                ->getResult();
    }

    public function findPostsFromFollowedUsers(User $user)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('u', 'c', 'l', 't')
            ->innerJoin('p.author', 'u')
            ->innerJoin('u.followers', 'f')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('p.likes', 'l')
            ->leftJoin('p.tags', 't')
            ->where('f.follower = :currentUser')
            ->setParameter('currentUser', $user)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findWithJoin(int $id): ?Post
    {
        return $this->createQueryBuilder('p')
            ->addSelect('pa', 'c', 'ca', 't', 'l')
            ->innerJoin('p.author', 'pa')
            ->leftJoin('p.comments', 'c')
            ->innerJoin('c.author', 'ca')
            ->leftJoin('p.tags', 't')
            ->leftJoin('c.likes', 'l')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findAllByUser(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.author = :user')
            ->addSelect('c', 'l', 't')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('p.likes', 'l')
            ->leftJoin('p.tags', 't')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }
    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
