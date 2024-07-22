<?php

namespace App\Controller;

use App\Entity\Chef;
use App\Form\ChefType;
use App\Repository\ChefRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chef')]
class ChefController extends AbstractController
{
    #[Route('/', name: 'chef_index', methods: ['GET'])]
    public function index(ChefRepository $chefRepository): Response
    {
        // Affiche la liste des chefs pour les administrateurs
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('chef/index.html.twig', [
                'chefs' => $chefRepository->findAll(),
            ]);
        }

        throw new AccessDeniedException('You do not have permission to access this page.');
    }

    #[Route('/{id}', name: 'chef_show', methods: ['GET'])]
    public function show(Chef $chef): Response
    {
        // Affiche le profil du chef uniquement au chef concerné
        if ($this->isGranted('ROLE_CHEF') && $this->getUser()->getChef() === $chef) {
            return $this->render('chef/show.html.twig', [
                'chef' => $chef,
            ]);
        }

        throw new AccessDeniedException('You do not have permission to view this profile.');
    }

    #[Route('/{id}/edit', name: 'chef_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chef $chef, EntityManagerInterface $entityManager): Response
    {
        // Permet au chef de modifier son propre profil
        if (!$this->isGranted('ROLE_CHEF') || $this->getUser()->getChef() !== $chef) {
            throw new AccessDeniedException('You do not have permission to edit this profile.');
        }

        $form = $this->createForm(ChefType::class, $chef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profile updated successfully.');
            return $this->redirectToRoute('chef_show', ['id' => $chef->getId()]);
        }

        return $this->render('chef/edit.html.twig', [
            'form' => $form->createView(),
            'chef' => $chef,
        ]);
    }
}




