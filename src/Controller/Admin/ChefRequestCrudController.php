<?php

namespace App\Controller\Admin;

use App\Entity\ChefRequest;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ChefRequestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ChefRequest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Chef Requests')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Chef Request Details')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Chef Request')
            ->setPageTitle(Crud::PAGE_NEW, 'New Chef Request')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextEditorField::new('reason');
        yield DateTimeField::new('createdAt')->onlyOnIndex();
        yield BooleanField::new('approved');
        yield BooleanField::new('rejected');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('approved')
            ->add('rejected')
            ->add('createdAt');
    }
}
