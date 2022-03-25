<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entitymanager)
    {
        $this->entityManager = $entitymanager;
    }

    #[Route('/commandes/merci/{stripeSessionId}', name: 'order_validate')]

    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }
        if ($order->getState() == 0){


             //vider la session "cart"
            $cart->remove();


            //Modifier le statut isPaid de notre commande en mettant 1 ( est payé )
            $order->setState(1);
            $this->entityManager->flush();

            // Envoyer un email à notre client pour lui confirmer sa commande

            $mail = new Mail();
            $content = 'Bonjour'.$order->getUser()->getFirstname()."<br/> Merci pour votre commande";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande est bien validée', $content);
            $notification='Votre inscription a été validée';

    }
        return $this->render('order_validate/index.html.twig', [
            'order'=>$order
        ]);
    }
}
