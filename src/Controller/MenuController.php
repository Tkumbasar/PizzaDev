<?php
namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MenuController extends AbstractController
{
    #[Route('/menus', name: 'menu_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $menus = $em->getRepository(Menu::class)->findAll();

        // Préparer les commentaires par ID de menu pour le rendu
        $commentsByMenu = [];
        foreach ($menus as $menu) {
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
    $user = $this->getUser();

    if (!$user || !in_array('ROLE_CUSTOMER', $user->getRoles(), true)) {
        return new JsonResponse(['success' => false, 'message' => 'Vous devez être un client pour ajouter un commentaire.'], Response::HTTP_UNAUTHORIZED);
    }

    $menu = $em->getRepository(Menu::class)->find($menuId);
    if (!$menu) {
        return new JsonResponse(['success' => false, 'message' => 'Menu not found.'], Response::HTTP_NOT_FOUND);
    }

    $text = $request->request->get('comment_text');
    if ($text) {
        $comment = new Comment();
        $comment->setComment($text);
        $comment->setCustomer($user->getCustomer());
        $comment->addMenu($menu);
        $comment->setCreatedAt(new \DateTimeImmutable());

         try {
            $em->persist($comment);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de l\'enregistrement du commentaire : ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

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

    return new JsonResponse(['success' => false, 'message' => 'Le commentaire ne peut pas être vide.']);
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
}