<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    
    // mis a jour des menu home
    
    #[Route('/', name: 'app_home' )]
    public function index(Request $request, MenuRepository $menuRepository,ProductRepository $productRepository): Response
    {
        $session = $request->getSession();
        $currentDate = (new \DateTime())->format('Y-m-d');

    // Vérifiez si les menus quotidiens sont déjà dans la session et s'ils sont pour aujourd'hui
        if ($session->has('daily_menus') && $session->get('currentDate') === $currentDate) {
            // Les menus quotidiens sont déjà définis pour aujourd'hui, rien à faire
            $menus = $session->get('daily_menus');
        } else {
            // Les menus quotidiens ne sont pas définis ou la date a changé, mettez-les à jour
            $menus = $menuRepository->findRandomMenus(3);
            $session->set('daily_menus', $menus);
            $session->set('currentDate', $currentDate);
        }

        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findAllPaginated($request->query->getInt('page', 2)),
            'menus' => $menus,
            'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY']
        ]);
    }
    
}
