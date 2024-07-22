<?php

namespace App\Controller;

use App\Entity\Chef;
use App\Entity\ChefRequest;
use App\Form\ChefRequestType;
use App\Repository\ChefRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChefRequestController extends AbstractController
{
    #[Route('/request', name: 'chef_request_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ChefRequestRepository $chefRequestRepository): Response
    {
        // User request handling
        if ($this->isGranted('ROLE_USER')) {
            $chefRequest = new ChefRequest();
            $chefRequest->setUser($this->getUser());

            $form = $this->createForm(ChefRequestType::class, $chefRequest);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($chefRequest);
                $entityManager->flush();

                $this->addFlash('success', 'Your request has been submitted.');
                return $this->redirectToRoute('chef_request_index');
            }

            return $this->render('chef_request/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        // Admin management handling
        if ($this->isGranted('ROLE_ADMIN')) {
            $requests = $chefRequestRepository->findAll();

            return $this->render('chef_request/index.html.twig', [
                'requests' => $requests,
            ]);
        }

        return $this->render('chef_request/index.html.twig', [
            'form' => null,
            'requests' => null,
        ]);
    }

    #[Route('/{id}/approve', name: 'chef_request_approve', methods: ['POST'])]
    public function approve(Request $request, ChefRequest $chefRequest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('approve' . $chefRequest->getId(), $request->request->get('_token'))) {
            $chefRequest->setApproved(true);
            $chefRequest->setRejected(false);
            $entityManager->flush();

            $user = $chefRequest->getUser();
            $chef = new Chef();
            $chef->setUserChef($user);
            $entityManager->persist($chef);
            $entityManager->flush();
        }

        return $this->redirectToRoute('chef_request_index');
    }

    #[Route('/{id}/reject', name: 'chef_request_reject', methods: ['POST'])]
    public function reject(Request $request, ChefRequest $chefRequest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('reject' . $chefRequest->getId(), $request->request->get('_token'))) {
            $chefRequest->setRejected(true);
            $chefRequest->setApproved(false);
            $entityManager->flush();
        }

        return $this->redirectToRoute('chef_request_index');
    }
}
