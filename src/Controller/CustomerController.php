<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'customer_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request): Response
    {
        $user = $this->getUser();

        // Vérifie si l'utilisateur est connecté avant de continuer
        if (!$user) {
            throw $this->createAccessDeniedException('User must be logged in.');
        }

        // Utiliser le repository pour vérifier si l'utilisateur a déjà un profil client
        $customer = $this->entityManager->getRepository(Customer::class)->findOneBy(['userCustomer' => $user]);

        // Si aucun profil client n'existe pour cet utilisateur, en créer un nouveau
        if (!$customer) {
            $customer = new Customer();
            $customer->setUserCustomer($user);
        }

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            $this->addFlash('success', 'Profile saved successfully!');
        }

        return $this->render('customer/index.html.twig', [
            'form' => $form->createView(),
            'customer' => $customer,
        ]);
    }

    #[Route('/profile/delete', name: 'customer_delete', methods: ['POST'])]
    public function delete(Request $request): Response
    {
        $user = $this->getUser();
        $customer = $this->entityManager->getRepository(Customer::class)->findOneBy(['userCustomer' => $user]);

        if (!$customer) {
            $this->addFlash('warning', 'You need to create a profile first.');
            return $this->redirectToRoute('customer_profile');
        }

        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($customer);
            $this->entityManager->flush();

            $this->addFlash('success', 'Profile deleted successfully!');
        } else {
            $this->addFlash('error', 'Failed to delete profile.');
        }

        return $this->redirectToRoute('app_home'); // Adapter cette route selon votre application
    }
}
