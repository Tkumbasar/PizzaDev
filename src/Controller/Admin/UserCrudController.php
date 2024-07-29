<?php
namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Doctrine\ORM\EntityManagerInterface;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email'),
            ChoiceField::new('roles')
                ->allowMultipleChoices()
                ->setChoices([
                    'User' => 'ROLE_USER',
                    'Customer' => 'ROLE_CUSTOMER',
                    'Admin' => 'ROLE_ADMIN',
                    'Chef' => 'ROLE_CHEF',
                ]),

            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->onlyOnForms(), // Assure que le mot de passe est uniquement dans les formulaires
            BooleanField::new('isVerified'),
        ];
    }

    // public function createEntity(string $entity)
    // {
    //     $entity = new User();
    //     // Définir isVerified à true lors de la création d'un utilisateur
    //     $entity->setVerified(true);
    //     return $entity;
    // }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->hashPassword($entityInstance);
        }
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->hashPassword($entityInstance);
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function hashPassword(User $user): void
    {
        if ($user->getPassword()) {
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);
            $user->setPassword($hashedPassword);
        }
    }

    public function removeEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
    
        if ($entityInstance instanceof User) {
            // Assurez-vous que l'entité est correcte avant de la supprimer
            $entityManager->remove($entityInstance);
            $entityManager->flush();
        }
    }
}

