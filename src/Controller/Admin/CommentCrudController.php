<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        return [
            TextEditorField::new('comment'),
            DateField::new('created_at'),
            AssociationField::new('customer', 'Client'),
            AssociationField::new('menus', 'Menus')
                ->formatValue(function ($value) {
                    // Récupérer et afficher les titres des menus associés
                    return implode(', ', array_map(function ($menu) {
                        return (string) $menu; // Utilise la méthode __toString() de Menu
                    }, $value->toArray()));
                })
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
        ];
    }
  
}
