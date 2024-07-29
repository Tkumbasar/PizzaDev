<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//le crud Product pour le chef

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/crud-product.html.twig', [
            'products' => $productRepository->findAll(),
            'action' => 'index',
        ]);
    }

    #[Route('/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/crud-product.html.twig', [
            'product' => $product,
            'form' => $form,
            'action' => 'new',
        ]);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/crud-product.html.twig', [
            'product' => $product,
            'action' => 'show',
        ]);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/crud-product.html.twig', [
            'product' => $product,
            'form' => $form,
            'action' => 'edit',
        ]);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/products/by-category', name: 'products_by_category')]
    public function listProductsByCategory(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ): Response {
        // Récupérer toutes les catégories
        $categories = $categoryRepository->findAll();
       
        // Préparer les produits par catégorie
        $productsByCategory = [];
        foreach ($categories as $category) {
            $productsByCategory[$category->getName()] = $productRepository->findBy(['category' => $category]);
        }
       

        // Rendre le template avec les variables nécessaires
        return $this->render('product/customer-product.html.twig', [
            'products_by_category' => $productsByCategory,
        ]);
    }
}
