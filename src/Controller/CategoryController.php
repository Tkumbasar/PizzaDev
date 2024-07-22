<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Menu;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CommentType;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id}/menus", name="category_menus", requirements={"id"="\d+"})
     */
    public function showCategoryMenus(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $category = $em->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée.');
        }

        // Récupérer tous les menus associés aux produits de la catégorie
        $menus = [];
        foreach ($category->getProducts() as $product) {
            foreach ($product->getMenus() as $menu) {
                if (!in_array($menu, $menus)) {
                    $menus[] = $menu;
                }
            }
        }

        $formViews = [];
        $commentsByMenu = [];
        if ($this->getUser()) {
            $customer = $this->getUser()->getCustomer();

            foreach ($menus as $menu) {
                $comment = new Comment();
                $form = $this->createForm(CommentType::class, $comment);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $comment->setCustomer($customer);
                    $comment->setMenu($menu);

                    $em->persist($comment);
                    $em->flush();

                    $this->addFlash('success', 'Votre commentaire a été ajouté.');
                    return $this->redirectToRoute('category_menus', ['id' => $id]);
                }

                $formViews[$menu->getId()] = $form->createView();

                // Récupérer les commentaires pour chaque Menu
                $commentsByMenu[$menu->getId()] = $menu->getComments();
            }
        }

        return $this->render('category/menus.html.twig', [
            'category' => $category,
            'menus' => $menus,
            'form_views' => $formViews,
            'comments_by_menu' => $commentsByMenu,
        ]);
    }
}


