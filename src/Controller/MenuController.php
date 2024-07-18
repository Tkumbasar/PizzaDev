<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class MenuController extends AbstractController
{
    private $menuRepository;
    private $cache;
    private EntityManagerInterface $entityManager;


    public function __construct(MenuRepository $menuRepository,EntityManagerInterface $entityManager,CacheInterface $cache)
    {
        $this->menuRepository = $menuRepository;
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }

    // #[Route('/', name: 'app_home')]
    // public function index(): Response
    // {
    //     $menus = $this->cache->get('daily_menus', function (ItemInterface $item) {
    //         $item->expiresAfter(86400); // 86400 secondes = 1 jour
    //         return $this->menuRepository->findRandomMenus(5);
    //     });

    //     return $this->render('home/index.html.twig', [
    //         'menus' => $menus,
    //     ]);
    // }

    public function loadCommentsAjax(Request $request): Response
    {
        $menuId = $request->request->get('menuId');
        $menu = $this->entityManager->getRepository(Menu::class)->find($menuId);

        if (!$menu) {
            throw $this->createNotFoundException('Menu non trouvé');
        }

        // Récupérer les commentaires du menu sous forme de tableau associatif
        $comments = [];
        foreach ($menu->getComments() as $comment) {
            $comments[] = [
                'content' => $comment->getContent(),
                'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'), // Formater la date comme souhaité
            ];
        }

        return $this->render('menu/comments_modal.html.twig', [
            'comments' => $comments,
        ]);
    }
}
