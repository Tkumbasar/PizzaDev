<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }


    public function findByMenu(Menu $menu)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.menu = :menu')
            ->setParameter('menu', $menu)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
