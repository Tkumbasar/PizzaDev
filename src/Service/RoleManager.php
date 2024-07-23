<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RoleManager
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private TokenStorageInterface $tokenStorage;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function assignRole(User $user, string $role): void
    {
        $currentUser = $this->tokenStorage->getToken()->getUser();

        if (!$this->isSuperAdmin($currentUser)) {
            throw new AccessDeniedException('Only super admins can assign this role.');
        }

        if ($role === 'ROLE_SUPER_ADMIN') {
            $existingSuperAdmin = $this->userRepository->findOneBy(['roles' => 'ROLE_SUPER_ADMIN']);
            if ($existingSuperAdmin && $existingSuperAdmin !== $user) {
                throw new AccessDeniedException('A super admin already exists.');
            }
        }

        if ($role === 'ROLE_ADMIN') {
            $existingAdmin = $this->userRepository->findOneBy(['roles' => 'ROLE_ADMIN']);
            if ($existingAdmin && $existingAdmin !== $user) {
                throw new AccessDeniedException('An admin already exists.');
            }
        }

        $roles = $user->getRoles();
        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $user->setRoles($roles);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    private function isSuperAdmin(User $user): bool
    {
        return in_array('ROLE_SUPER_ADMIN', $user->getRoles(), true);
    }
}
