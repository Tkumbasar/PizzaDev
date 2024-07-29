<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Chef;
use App\Entity\Comment;
use App\Entity\Customer;
use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(MenuCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('PizzaDev');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Menus', 'fas fa-list', Menu::class);
    yield MenuItem::linkToCrud('Customer', 'fas fa-users', Customer::class);
    yield MenuItem::linkToCrud('Chef', 'fas fa-user', Chef::class); // Exemple d'icône personnalisée, "fas fa-user" pour un chef
    yield MenuItem::linkToCrud('Product', 'fas fa-box', Product::class); // "fas fa-box" pour un produit
    yield MenuItem::linkToCrud('Category', 'fas fa-tags', Category::class); // "fas fa-tags" pour une catégorie
    yield MenuItem::linkToCrud('User', 'fas fa-user', User::class); // "fas fa-user" pour un utilisateur
    yield MenuItem::linkToCrud('Order', 'fas fa-shopping-cart', Order::class);
    yield MenuItem::linkToCrud('Comment', 'fas fa-comment', Comment::class); // "fas fa-comment" pour un commentaire
    yield MenuItem::linkToCrud('Payment', 'fas fa-credit-card', Payment::class);
       
    }
}
