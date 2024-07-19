<?php

namespace App\Controller;

use App\Form\MenuType;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
   private $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
       $this->menuRepository= $menuRepository;
    }

    #[Route('/menuChef', name: 'app_menu')]
    public function index(): Response
    {
        $menus = $this->menuRepository->findAll();

        return $this->render('home/index.html.twig', [ // ici la vue pour Crud des menus par le role chef
            'menus' => $menus,
        ]);
    }

    // public function new (Request $request): Response
    // {
    //     $menu =new Menu();
    //     $form = $this->createForm(MenuType::class,$menu);
    //     $form ->handleRequest($request);
    // }
}
