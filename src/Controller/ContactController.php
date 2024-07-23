<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
       $form = $this->createForm(ContactType::class);
       $form->handleRequest($request); 

        if ($form->isSubmitted()&& $form->isValid()) {
            $data = $form->getData();

            $address = $data ['email'];
            $content = $data ['content'];

        $email = (new Email())
            ->from($address)
            ->to('Ouskour@example.com')
            ->subject('demande de contact')
            ->text($content);
            

            $mailer->send($email);
        }
        return $this->render('contact/index.html.twig', [  
            'formulaire'=>$form->createView(),
        ]);
    }
}
