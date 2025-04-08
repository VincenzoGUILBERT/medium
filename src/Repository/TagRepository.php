<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findallWithJoin($page): PaginationInterface
    {
        $qb =  $this->createQueryBuilder('t')
            ->select('t.name as name','t.id as id', 'COUNT(p.id) as postCount')
            ->leftJoin('t.posts', 'p')
            ->groupBy('t.id')
            ->getQuery()
            ->getResult()
        ;

        return $this->paginator->paginate($qb, $page, 25);
    }

    //    public function findOneBySomeField($value): ?Tag
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
