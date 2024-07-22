<?php

namespace App\Controller\Admin;

use App\Entity\Chef;
use App\Entity\User;
use App\Form\ChefType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserCrudController extends AbstractCrudController
{

    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function configureActions(Actions $actions): Actions
    {
        // Action pour convertir en chef
        $convertToChefAction = Action::new('convertToChef', 'Convert to Chef')
            ->linkToRoute('admin_convert_to_chef', function (User $user) {
                return ['id' => $user->getId()];
            });

        return $actions
            ->add(Crud::PAGE_INDEX, $convertToChefAction);
    }
 
    #[Route('/admin/user/{id}/convert-to-chef', name:'admin_convert_to_chef')]
    public function convertToChef(Request $request, User $user):Response
    {

        $currentUser = $this->security->getUser();
        if (!$currentUser || !in_array('ROLE_ADMIN', $currentUser->getRoles(), true)) {
            $this->addFlash('error', 'You do not have permission to perform this action.');
            return $this->redirect($request->headers->get('referer') ?: $this->generateUrl('admin_convert_to_chef'));

        }

        // Vérifiez si l'utilisateur est déjà un chef
        if (in_array('ROLE_CHEF', $user->getRoles(), true)) {
            $this->addFlash('error', 'User is already a chef.');
            return MenuItem::linkToCrud('Chef', 'fas fa-utensils', Chef::class);
        }

        // Créez un nouvel objet Chef
        $chef = new Chef();
        $chef->setUserChef($user); // Associez l'utilisateur au Chef

        $form = $this->createForm(ChefType::class, $chef);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder le chef
            $this->entityManager->persist($chef);
            $this->entityManager->flush();

            // Mettre à jour les rôles de l'utilisateur
            $user->setRoles(['ROLE_CHEF']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            // Supprimez l'utilisateur de la base de données
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'User has been successfully converted to a chef.');

            return MenuItem::linkToCrud('Chef', 'fas fa-utensils', Chef::class);// Remplacez par la route appropriée
        };

        return $this->render('components/convertToChef.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
          
            TextField::new('email'),
            TextField::new('password'),
            ChoiceField::new('roles')
            ->setChoices([
                'Chef'=>'ROLE_CHEF',
                ])
                ->allowMultipleChoices(true),
                TextField::new('password')->onlyOnForms(),
        ];
    }
  
}
