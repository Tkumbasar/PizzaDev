<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findRandomMenus(int $limit = 3): array
    {
        // Récupération de l'EntityManager
        $entityManager = $this->getEntityManager();

        // Construction de la requête
        $query = $entityManager->createQuery(
            'SELECT menu FROM App\Entity\Menu menu LEFT JOIN menu.products product'
        );

        // Exécution de la requête pour obtenir tous les résultats
        $results = $query->getResult();

        // Mélange aléatoire des résultats
        shuffle($results);

        // Limiter le nombre de résultats à $limit
        $randomMenus = array_slice($results, 0, $limit);

        return $randomMenus;
    }

    //    /**
    //     * @return Menu[] Returns an array of Menu objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

       
}
