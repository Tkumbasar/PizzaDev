<?php

namespace App\Controller;

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
       
    }

    // #[Route('/', name: 'app_home')]
    // public function index(): Response
    // {
    //     $menus = $this->menuRepository->findAll();

    //     return $this->render('home/index.html.twig', [
    //         'menus' => $menus,
    //     ]);
    // }

}
