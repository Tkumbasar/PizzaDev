<?php

namespace App\Controller\Admin;

use App\Entity\Chef;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ChefCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Chef::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('name'),
            TextField::new('firstname'),
            DateField::new('date_of_birthday'),
            TelephoneField::new('phone'),
            ImageField::new('picture')->setUploadDir('public/images/images_chef'),
            ChoiceField::new('gender', 'Gender')
                ->setChoices([
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ]),
            AssociationField::new('userChef')
        ];
    }
    
}
