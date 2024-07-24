<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer, Security $security): Response
    {

       $form = $this->createForm(ContactType::class);
       $form->handleRequest($request); 

        if ($form->isSubmitted()&& $form->isValid()) {
            if (!$security->isGranted('ROLE_USER')) {
                $this->addFlash('error', 'Vous devez être connecté pour envoyer un message.');
                return $this->redirectToRoute('app_contact');
            }
            $data = $form->getData();

            $address = $data ['email'];
            $content = $data ['content'];

        $email = (new Email())
            ->from($address)
            ->to('Ouskour@example.com')
            ->subject('demande de contact')
            ->text($content);
            

            try {
                $mailer->send($email);
                $this->addFlash('success', 'Votre message a été envoyé avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre message.');
            }

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/contact.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }
}