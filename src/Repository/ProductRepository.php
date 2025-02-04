<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;



class ProductRepository extends ServiceEntityRepository

{
    
    private PaginatorInterface $paginatorInterface;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Product::class);
        $this->paginatorInterface = $paginatorInterface;
    
    }
    
    /**
     * @param int $page
     * @return PaginationInterface
     */


     public function findAllPaginated(int $page): PaginationInterface
     {
            $query = $this->createQueryBuilder('p')
             ->orderBy('p.id', 'ASC')
             ->getQuery();
          
            
            $products = $this->paginatorInterface->paginate(
            $query, // Query à paginer
            $page, // Numéro de page
            3// Nombre d'éléments par page
           );

           return $products;
       }
       public function findAllGroupedByCategory(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->addSelect('c')
            ->orderBy('c.name', 'ASC')
            ->addOrderBy('p.name', 'ASC');

        $results = $qb->getQuery()->getResult();

        $grouped = [];
        foreach ($results as $product) {
            $categoryName = $product->getCategory() ? $product->getCategory()->getName() : 'Non classifié';
            if (!isset($grouped[$categoryName])) {
                $grouped[$categoryName] = [];
            }
            $grouped[$categoryName][] = $product;
        }

        return $grouped;
    }


//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
