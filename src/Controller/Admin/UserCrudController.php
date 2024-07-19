<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            TextField::new('password'),
            ChoiceField::new('roles')
            ->setChoices([
                'Customer'=>'ROLE_CUSTOMER',
                'Chef'=>'ROLE_CHEF',
                ])
                ->allowMultipleChoices(true),
                TextField::new('password')->onlyOnForms(),
        ];
    }
  
}
