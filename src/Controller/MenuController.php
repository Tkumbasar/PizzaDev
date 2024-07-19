<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class MenuController extends AbstractController
{
    private $menuRepository;
    private $entityManager;

    public function __construct(MenuRepository $menuRepository, EntityManagerInterface $entityManager)
    {
       $this->menuRepository= $menuRepository;
       $this->entityManager= $entityManager;
    }


    
    #[Route('/menu', name: 'app_menu', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $menus = $this->menuRepository->findAll();
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($menu);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_menu');
        }

        return $this->render('menu/index.html.twig', [
            'menus' => $menus,
            'form' => $form->createView(),
        ]);
    }

    
    #[Route('/edit/{id}', name: 'menu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Menu $menu): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_menu');
        }

        $menus = $this->menuRepository->findAll();
        return $this->render('menu/index.html.twig', [
            'menus' => $menus,
            'form' => $form->createView(),
            'menu' => $menu,
        ]);
    }

    #[Route('/delete/{id}', name: 'menu_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Menu $menu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menu->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($menu);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_menu');
    }
    
}
