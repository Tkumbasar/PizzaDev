<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\RoleManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminRoleManagerController extends AbstractController
{
    private RoleManager $roleManager;

    public function __construct(RoleManager $roleManager)
    {
        $this->roleManager = $roleManager;
    }

    /**
     * @Route("/admin/assign/{id}", name="admin_assign", methods={"POST"})
     */
    public function assignAdmin(User $user): Response
    {
        try {
            $this->roleManager->assignRole($user, 'ROLE_ADMIN');
        } catch (AccessDeniedException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('user_list');
        }

        $this->addFlash('success', 'Admin role assigned successfully.');
        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("/superadmin/assign/{id}", name="superadmin_assign", methods={"POST"})
     */
    public function assignSuperAdmin(User $user): Response
    {
        try {
            $this->roleManager->assignRole($user, 'ROLE_SUPER_ADMIN');
        } catch (AccessDeniedException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('user_list');
        }

        $this->addFlash('success', 'Super Admin role assigned successfully.');
        return $this->redirectToRoute('user_list');
    }
}
