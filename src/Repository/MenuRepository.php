<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }
    

    /**
     * Trouve un nombre aléatoire de menus.
     *
     * @param int $limit Nombre de menus à retourner.
     *
     * @return Menu[] Tableau de menus.
     */
    public function findRandomMenus(int $limit): array
    {
      // Récupérer tous les menus
      $allMenus = $this->findAll();

      // Mélanger les menus
      shuffle($allMenus);

      // Limiter les résultats à $limit
      return array_slice($allMenus, 0, $limit);
  }

}
