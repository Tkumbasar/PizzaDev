<?php

namespace App\Controller;

use App\Entity\Chef;
use App\Entity\User;
use App\Form\ChefType;
use App\Repository\ChefRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ChefController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/chef', name: 'chef_index', methods: ['GET', 'POST'])]
    public function profile(Request $request,UserRepository $user ,ChefRepository $chefRepository ,TokenStorageInterface $tokenStorage): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $chef = $chefRepository->findOneBy(['userChef' => $user]);

        if (!$chef) {
            $chef  = new Chef();
            $chef ->setUserChef($user);
        }

        $form = $this->createForm(ChefType::class, $chef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $chef->setImageFile($imageFile);
            }

            if (!in_array('ROLE_CHEF', $user->getRoles(), true)) {
                $roles = array_merge($user->getRoles(), ['ROLE_CHEF']);
                $user->setRoles($roles);
                $this->entityManager->persist($user);
            }
            
            $this->entityManager->persist($chef);
            $this->entityManager->flush();

            //on reco l'utisateur car il change de rôle
            $token = new UsernamePasswordToken($user,'main', $user->getRoles());
            $tokenStorage->setToken($token);

            $this->addFlash('success', 'Profile saved successfully!');
            return $this->redirectToRoute('chef_index');
        }
        
        return $this->render('chef/index.html.twig', [
            'form' => $form->createView(),
            'chef' => $chef,
        ]);
    }

    #[Route('/chef/delete', name: 'chef_delete', methods: ['POST'])]
    public function delete(ChefRepository $chefRepository ,TokenStorageInterface $tokenStorage):RedirectResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $chef = $chefRepository->findOneBy(['userChef' => $user]);
        
    

        if (!$chef) {
            return new JsonResponse(['message' => 'Profile not found.'], Response::HTTP_BAD_REQUEST);
        }
       
        $this->entityManager->remove($chef);
        $this->entityManager->flush();
        

        $user->setRoles(array_diff($user->getRoles(), ['ROLE_CHEF']));
        $user->setChef(null);
      
       
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $token = new UsernamePasswordToken($user,"main", $user->getRoles());
        $tokenStorage->setToken($token);

        $this->addFlash('success', 'Profile deleted successfully!');
        return $this->redirectToRoute('app_home');
    }


}



   

    

   





