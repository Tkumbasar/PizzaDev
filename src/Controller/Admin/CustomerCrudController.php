<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CustomerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Customer::class;
    }

  
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('name'),
            TextField::new('firstname'),
            ChoiceField::new('gender', 'Gender')
                ->setChoices([
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ]),
            DateField::new('date_of_birthday'),
            TelephoneField::new('phone'),
            TextField::new('city'),
            TextField::new('adress','Adress'),
        ];
    }
    
}
