<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/checkout/{orderId}', name: 'order_checkout')]
    public function checkout(int $orderId, EntityManagerInterface $entityManager): Response
    {
        $order = $entityManager->getRepository(Order::class)->find($orderId);

        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        // Créer un nouvel objet Payment
        $payment = new Payment();
        $payment->setCreatedAt(new \DateTimeImmutable());
        $payment->setAmount($order->getTotalPrice()); // Montant du paiement
        $payment->setPaymentMethod('Credit Card'); // Méthode de paiement fixe pour l'exemple

        // Associer le paiement à la commande
        $order->setOrderPayment($payment);

        // Persister le paiement et la commande
        $entityManager->persist($payment);
        $entityManager->persist($order);
        $entityManager->flush();

        // Rendre la vue avec les détails de la commande et du paiement
        return $this->render('order/checkout.html.twig', [
            'order' => $order,
            'payment' => $payment,
        ]);
    }
}

