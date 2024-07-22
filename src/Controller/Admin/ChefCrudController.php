<?php
namespace App\Controller\Admin;

use App\Entity\Chef;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class ChefCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Chef::class; // Utilisez User::class si vous gérez des utilisateurs avec des rôles
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Chef')
            ->setEntityLabelInPlural('Chefs')
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('roles', 'Role')
                ->setChoices([
                    'Chef' => 'ROLE_CHEF',
                    'Customer' => 'ROLE_CUSTOMER',
                    // Ajoutez d'autres rôles si nécessaire
                ])
                ->setFormTypeOption('expanded', true) // Optionnel : pour des cases à cocher
                ->setFormTypeOption('multiple', true) // Optionnel : pour permettre la sélection multiple
            );
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('firstname'),
            DateField::new('date_of_birthday'),
            TelephoneField::new('phone'),
            ImageField::new('picture')
                ->setUploadDir('public/images/images_chef')
                ->setBasePath('images/images_chef'), // Optionnel : pour afficher les images
            ChoiceField::new('gender', 'Gender')
                ->setChoices([
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ]),
            AssociationField::new('userChef') // Assurez-vous que 'userChef' est un champ valide dans User ou Chef
        ];
    }
}

