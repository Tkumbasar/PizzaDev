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

        $customer = $this->entityManager->getRepository(Customer::class)->findOneBy(['userCustomer' => $user]);

        if (!$customer) {
            $customer = new Customer();
            $customer->setUserCustomer($user);
        }

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!in_array('ROLE_CUSTOMER', $user->getRoles(), true)) {
                $roles = array_merge($user->getRoles(), ['ROLE_CUSTOMER']);
                $user->setRoles($roles);
                $this->entityManager->persist($user);
            }

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

        $customer = $this->entityManager->getRepository(Customer::class)->findOneBy(['userCustomer' => $user]);

        if (!$customer) {
            return new JsonResponse(['message' => 'Profile not found.'], Response::HTTP_BAD_REQUEST);
        }
       

        $this->entityManager->remove($customer);
        $this->entityManager->flush();
        // dd('$customer');
        $user->setRoles(array_diff($user->getRoles(), ['ROLE_CUSTOMER']));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'Profile deleted successfully!');
        return $this->redirectToRoute('app_home');
    }
}
