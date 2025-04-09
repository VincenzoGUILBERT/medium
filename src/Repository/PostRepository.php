<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);
    }

    public function findFilteredPosts($page, $tag = null, $sort = null, $user = null): PaginationInterface
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('a', 't', 'c', 'l')
            ->innerJoin('p.author', 'a')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('p.likes', 'l')
            ->leftJoin('p.tags', 't');

        if ($tag) {
            $qb->andWhere(':tag MEMBER OF p.tags')
                ->setParameter('tag', $tag);
        }

        switch ($sort) {
            case 'new':
                $qb->orderBy('p.createdAt', 'DESC');
                break;
            case 'populars':
                $qb->orderBy('l', 'DESC');
            case 'following':
                $qb->innerJoin('a.followers', 'f')
                    ->where('f.follower = :currentUser')
                    ->setParameter('currentUser', $user);
            default:
                $qb->orderBy('p.createdAt', 'DESC');
                break;
        }
        return $this->paginator->paginate($qb, $page, 10);
    }

    public function findWithJoin(int $id): ?Post
    {
        return $this->createQueryBuilder('p')
            ->addSelect('p', 't', 'l')
            ->innerJoin('p.author', 'a')
            ->leftJoin('p.tags', 't')
            ->leftJoin('p.likes', 'l')
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
