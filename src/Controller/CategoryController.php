<?php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category', methods: ['GET', 'POST'])]
    public function index(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        // Création de la catégorie
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie créée avec succès !');
            return $this->redirectToRoute('app_category');
        }

        // Suppression de la catégorie
        if ($request->isMethod('POST')) {
            $id = $request->request->get('delete_id');
            $categoryToDelete = $categoryRepository->find($id);

            if ($categoryToDelete) {
                $entityManager->remove($categoryToDelete);
                $entityManager->flush();
                $this->addFlash('success', 'Catégorie supprimée avec succès !');
                return $this->redirectToRoute('app_category');
            }
        }

        // Récupération de toutes les catégories
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }
}
