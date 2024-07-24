<?php
namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Comment;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MenuController extends AbstractController
{

    #[Route('/menus', name: 'menus_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $menus = $em->getRepository(Menu::class)->findAll();

    // Préparation du tableau des commentaires par menu
        $commentsByMenu = [];
        foreach ($menus as $menu) {
        // Assurez-vous que getComments() retourne une collection valide
        $commentsByMenu[$menu->getId()] = $menu->getComments()->toArray();
    }

        return $this->render('menu/menu.html.twig', [
            'menus' => $menus,
            'comments_by_menu' => $commentsByMenu,
        ]);
    }

   
    #[Route('/menu/{menuId}/comment/add', name: 'comment_add', methods: ['POST'])]
    public function addComment(Request $request, int $menuId, EntityManagerInterface $em): JsonResponse
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        // Vérification de l'utilisateur et de son rôle
        if (!$user || !in_array('ROLE_CUSTOMER', $user->getRoles(), true)) {
            return new JsonResponse([
                'success' => false, 
                'message' => 'Vous devez être un client pour ajouter un commentaire.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Vérification de l'existence du menu
        $menu = $em->getRepository(Menu::class)->find($menuId);
        if (!$menu) {
            return new JsonResponse([
                'success' => false, 
                'message' => 'Menu not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        // Récupération du texte du commentaire
        $text = $request->request->get('comment_text');
        if (empty($text)) {
            return new JsonResponse([
                'success' => false, 
                'message' => 'Le commentaire ne peut pas être vide.'
            ], Response::HTTP_BAD_REQUEST);
        }

        
        $comment = new Comment();
        $comment->setComment($text);
        $comment->setCustomer($user->getCustomer());
        $comment->addMenu($menu);
        $comment->setCreatedAt(new \DateTimeImmutable());

        try {
            
            $em->persist($comment);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false, 
                'message' => 'Erreur lors de l\'enregistrement du commentaire : ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Rendu du commentaire avec le template Twig
        $renderedComment = $this->renderView('comment/_comment.html.twig', ['comment' => $comment]);

        return new JsonResponse([
            'success' => true,
            'comment' => [
                'id' => $comment->getId(),
                'comment' => $comment->getComment(),
                'customerName' => $comment->getCustomer()->getName() ?? 'Anonyme',
            ],
            'comment_html' => $renderedComment
        ]);
    }

    #[Route('/comment/{commentId}/remove', name: 'comment_remove', methods: ['POST'])]
    public function removeComment(int $commentId, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user || !in_array('ROLE_CUSTOMER', $user->getRoles(), true)) {
            return new JsonResponse(['success' => false, 'message' => 'Vous devez être un client pour supprimer un commentaire.'], Response::HTTP_UNAUTHORIZED);
        }

        $comment = $em->getRepository(Comment::class)->find($commentId);
        if ($comment && $comment->getCustomer() === $user->getCustomer()) {
            foreach ($comment->getMenus() as $menu) {
                $menu->removeComment($comment);
            }

            $em->remove($comment);
            $em->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la suppression du commentaire.']);
    }



     ///// crud de menu pour le chef !
   

     #[Route('/menu', name: 'menu_index')]
    public function index(MenuRepository $menuRepository): Response
    {
        $menus = $menuRepository->findAll();

        return $this->render('menu/crud-chef.html.twig', [
            'action' => 'list',
            'menus' => $menus,
        ]);
    }

    #[Route('/new', name: 'menu_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($menu);
            $entityManager->flush();

            return $this->redirectToRoute('menu_new');
        }

        return $this->render('menu/crud-chef.html.twig', [
            'action' => 'new',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/menu/{id}/edit', name: 'menu_edit')]
    public function edit(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('menu_edit');
        }

        return $this->render('menu/crud-chef.html.twig', [
            'action' => 'edit',
            'form' => $form->createView(),
            'menu' => $menu,
        ]);
    }

    #[Route('/menu/{id}/delete', name: 'menu_delete', methods: ['POST'])]
    public function delete(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $menu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($menu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('menu_delete');
    }

}