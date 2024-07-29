<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    
    #[Route('/add_comment/{menu_id}', name:'add_comment', methods:["POST"])]
    
    public function addComment(Request $request, EntityManagerInterface $em, int $menu_id): Response
    {
        $menu = $em->getRepository(Menu::class)->find($menu_id);
        if (!$menu) {
            throw $this->createNotFoundException('Menu not found');
        }

        $commentText = $request->request->get('comment_text');

        $comment = new Comment();
        $comment->setComment($commentText);
        $comment->setCustomer($this->getUser()->getCustomer());
        $comment->getMenu()->add($menu);

        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('menus_list');
    }

    
    #[Route('/delete_comment/{id}', name:'delete_comment', methods:["POST"])]
     
    public function deleteComment(int $id, EntityManagerInterface $em): Response
    {
        $comment = $em->getRepository(Comment::class)->find($id);
        if (!$comment) {
            throw $this->createNotFoundException('Comment not found');
        }

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('menus_list');
    }
}