<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;


class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        return [
           
            IntegerField::new('total_price'),
            AssociationField::new('payment') 
            ->setFormTypeOptions([
                'by_reference' => false,
            ]),
        ];
    }
   
}
