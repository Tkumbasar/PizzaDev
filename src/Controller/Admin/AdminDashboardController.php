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
        yield MenuItem::linkToCrud('Menus', 'fas fa-utensils', Menu::class);
        yield MenuItem::linkToCrud('Customer', 'fas fa-utensils', Customer::class);
        yield MenuItem::linkToCrud('Chef', 'fas fa-utensils', Chef::class);
        yield MenuItem::linkToCrud('Product', 'fas fa-utensils', Product::class);
        yield MenuItem::linkToCrud('Category', 'fas fa-utensils', Category::class);
        yield MenuItem::linkToCrud('User', 'fas fa-utensils', User::class);
        yield MenuItem::linkToCrud('Order', 'fas fa-utensils', Order::class);
        yield MenuItem::linkToCrud('Comment', 'fas fa-utensils', Comment::class);
        yield MenuItem::linkToCrud('Payment', 'fas fa-utensils', Payment::class);
       
    }
}
