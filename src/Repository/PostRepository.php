<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function findWithJoin(int $id): ?Post
    {
        return $this->createQueryBuilder('p')
            ->addSelect('pa', 'c', 'ca')
            ->innerJoin('p.author', 'pa')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('c.author', 'ca')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
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
