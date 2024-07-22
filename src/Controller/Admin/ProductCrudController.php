<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

  
    public function configureFields(string $pageName): iterable
    {
        return [
    
            TextField::new('name'),
            TextEditorField::new('description'),
            ImageField::new('picture')
            ->setUploadDir('public/images/product_images')
            ->setBasePath('images/images_chef'),
            MoneyField::new('price')-> setCurrency('EUR'),
            AssociationField::new('category')
        ];
    }
  
}
