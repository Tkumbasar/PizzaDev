<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Chef;
use Doctrine\ORM\EntityManagerInterface;

class ProfileCompletionService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Vérifie si le profil de l'utilisateur est complet.
     */
    public function isProfileComplete($user): bool
    {
        $profile = $this->getProfileForUser($user);

        if (!$profile) {
            return false;
        }

        // Vérifiez si le profil est complet en fonction de son type
        return $this->isProfileEntityComplete($profile);
    }

    /**
     * Récupère le profil associé à l'utilisateur.
     */
    private function getProfileForUser($user): ?object
    {
        if (!$user) {
            return null;
        }

        // Récupérez le profil Customer si l'utilisateur a le rôle CUSTOMER
        if (in_array('ROLE_CUSTOMER', $user->getRoles(), true)) {
            return $this->entityManager->getRepository(Customer::class)->findOneBy(['userCustomer' => $user]);
        }

        // Récupérez le profil Chef si l'utilisateur a le rôle CHEF
        if (in_array('ROLE_CHEF', $user->getRoles(), true)) {
            return $this->entityManager->getRepository(Chef::class)->findOneBy(['userChef' => $user]);
        }

        return null;
    }

    /**
     * Vérifie si l'entité de profil est complète.
     */
    private function isProfileEntityComplete(object $profile): bool
    {
        $reflectionClass = new \ReflectionClass($profile);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($profile);

            if (is_string($value) && empty($value)) {
                return false;
            }
            if (is_array($value) && empty($value)) {
                return false;
            }
        }

        return true;
    }
}