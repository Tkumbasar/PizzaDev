<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User must be logged in.'], Response::HTTP_FORBIDDEN);
        }

        $user = $this->entityManager->getRepository(User::class)->find($user->getId());
        $customer = $this->entityManager->getRepository(Customer::class)->findOneBy(['userCustomer' => $user]);

        if (!$customer) {
            $customer = new Customer();
            $customer->setUserCustomer($user);
        }

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_CUSTOMER']);
            $this->entityManager->persist($user);
            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            $this->addFlash('success', 'Profile saved successfully!');
            return $this->redirectToRoute('customer_profile');
        }

        return $this->render('customer/index.html.twig', [
            'form' => $form->createView(),
            'customer' => $customer,
        ]);
    }

    #[Route('/profile/delete', name: 'customer_delete', methods: ['POST'])]
    public function delete(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User must be logged in.'], Response::HTTP_FORBIDDEN);
        }

        $user = $this->entityManager->getRepository(User::class)->find($user->getId());
        $customer = $this->entityManager->getRepository(Customer::class)->findOneBy(['userCustomer' => $user]);

        if (!$customer) {
            return new JsonResponse(['message' => 'You need to create a profile first.'], Response::HTTP_BAD_REQUEST);
        }

        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($customer);

            $user->setRoles(['ROLE_USER']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Profile deleted successfully!');
            return new JsonResponse([
                'message' => 'Profile deleted successfully!',
                'redirect' => $this->generateUrl('homepage') // Assurez-vous que 'homepage' est bien défini
            ]);
        } else {
            return new JsonResponse(['message' => 'Failed to delete profile.'], Response::HTTP_BAD_REQUEST);
        }
    }
}

