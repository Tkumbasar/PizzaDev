<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        return [
            
            IntegerField::new('price'),
            ImageField::new('picture')->setUploadDir('public/images/menu_images/'),
            TextEditorField::new('description'),
            TextField::new('title'),
            AssociationField::new('chef'),
            AssociationField::new('products')->setFormTypeOptions([
                'by_reference' => false,
            ])
        ];
    }

}
