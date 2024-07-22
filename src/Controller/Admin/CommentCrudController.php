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
            AssociationField::new('customer'),
            AssociationField::new('menus')->setFormTypeOptions([
                'by_reference' => false,
            ]),
        ];
    }
  
}
