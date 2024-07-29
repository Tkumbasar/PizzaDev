<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
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
            
            MoneyField::new('price')-> setCurrency('EUR'),
            ImageField::new('picture')
                ->setUploadDir('public/images/menu_images')
                ->setBasePath('images/menu_images')
                ->setRequired(false),
            TextEditorField::new('description'),
            TextField::new('title'),
            AssociationField::new('chef'),
            AssociationField::new('products', 'Produits')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'choice_label' => 'name', // Utilisez 'name' pour afficher le nom des produits
                ])
        ];
    }

}
