<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Menu;
use App\Form\CommentType;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//le crud menu pour le chef

#[Route('/menu')]
class MenuController extends AbstractController
{

    #[Route('/', name: 'menu_index', methods: ['GET'])]
    public function index(MenuRepository $menuRepository): Response
    {
        return $this->render('menu/crud-chef-menu.html.twig', [
            'menus' => $menuRepository->findAll(),
            'action' => 'index',
        ]);
    }

    #[Route('/new', name: 'menu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($menu);
            $entityManager->flush();

            $this->addFlash('success', 'Menu créé avec succès.');

            return $this->redirectToRoute('menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('menu/crud-chef-menu.html.twig', [
            'menu' => $menu,
            'form' => $form->createView(),
            'action' => 'new',
        ]);
    }

    #[Route('/{id}', name: 'menu_show', methods: ['GET'])]
    public function show(Menu $menu): Response
    {
        return $this->render('menu/crud-chef-menu.html.twig', [
            'menu' => $menu,
            'action' => 'show',
        ]);
    }

    #[Route('/{id}/edit', name: 'menu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Menu mis à jour avec succès.');

            return $this->redirectToRoute('menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('menu/crud-chef-menu.html.twig', [
            'menu' => $menu,
            'form' => $form->createView(),
            'action' => 'edit',
        ]);
    }

    #[Route('/{id}', name: 'menu_delete', methods: ['POST'])]
    public function delete(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($menu);
            $entityManager->flush();

            $this->addFlash('success', 'Menu supprimé avec succès.');
        }

        return $this->redirectToRoute('menu_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/list/all', name: 'menus_list')]
    public function list(EntityManagerInterface $em, Request $request, MenuRepository $menuRepository): Response
    {
    // Récupérer tous les menus
    /**
     * @var User $user
     */
    $user = $this->getUser();
    $menus = $em->getRepository(Menu::class)->findAll();
    
    // Préparer les commentaires par menu
    $commentsByMenu = [];
    foreach ($menus as $menu) {
        $commentsByMenu[$menu->getId()] = $menu->getComments();
    }
    $comment = new Comment();

    if($request->isMethod('post')) {
        
        $menuId = $request->get('menu_id');
        $commentText = $request->get('comment');
        $menuSelected = $menuRepository->find(['id' => $menuId]);

        $comment->setComment($commentText);
        $comment->addMenu($menuSelected);
        $comment->setCustomer($user->getCustomer());

        $em->persist($comment);
        $em->flush();
    }

    // Rendre le template avec les variables nécessaires
    return $this->render('menu/listToMenus.html.twig', [
        'menus' => $menus,
        'comments_by_menu' => $commentsByMenu, // Assurer que cette variable est passée

    ]);
}
    #[Route('/menus', name: 'app_menus')]
    public function menuComment(EntityManagerInterface $entityManager): Response
    {
        $menus = $entityManager->getRepository(Menu::class)->findAll();

        return $this->render('menu/listToMenu.html.twig', [
            'menus' => $menus,
        ]);
    }
    #[Route('/menu/{id}', name: 'app_menu_show', requirements: ['id' => '\d+'])]
    public function showComment(Menu $menu, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $newComment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $newComment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $newComment->setCustomer($security->getUser());
            $newComment->setComment($menu);

            $entityManager->persist($newComment);
            $entityManager->flush();

            return $this->redirectToRoute('app_menu_show', ['id' => $menu->getId()]);
        }

        return $this->render('menu/show.html.twig', [
            'menu' => $menu,
            'commentForm' => $commentForm->createView(),
        ]);
    }
}
