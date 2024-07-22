<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Service\ProfileCompletionService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Customer;
use App\Entity\Chef;

class LoginSuccessListener
{
    private RouterInterface $router;
    private RequestStack $requestStack;
    private EntityManagerInterface $entityManager;
    private ProfileCompletionService $profileCompletionService;

    public function __construct(
        RouterInterface $router, 
        RequestStack $requestStack, 
        EntityManagerInterface $entityManager, 
        ProfileCompletionService $profileCompletionService
    ) {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
        $this->profileCompletionService = $profileCompletionService;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();

        // Vérifiez si l'utilisateur est de type UserInterface
        if (!$user instanceof UserInterface) {
            return;
        }

        // Vérifiez les rôles de l'utilisateur
        if (in_array('ROLE_CUSTOMER', $user->getRoles(), true)) {
            $profile = $this->entityManager->getRepository(Customer::class)->findOneBy(['userCustomer' => $user]);
        } elseif (in_array('ROLE_CHEF', $user->getRoles(), true)) {
            $profile = $this->entityManager->getRepository(Chef::class)->findOneBy(['userChef' => $user]);
        } else {
            $profile = null;
        }

        // Vérifiez si le profil est complet
        if ($profile && !$this->profileCompletionService->isProfileComplete($profile)) {
            $request = $this->requestStack->getCurrentRequest();
            if ($request) {
                // Effectuez la redirection
                $response = new RedirectResponse($this->router->generate('user_profile_edit'));
                $response->send(); // Envoyez la réponse de redirection immédiatement
            }
        }
    }
}
