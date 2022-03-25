<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Products;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/commandes/create-session/{reference}', name: 'stripe_create_session')]

    public function index( EntityManagerInterface $entityManager, Cart $cart, $reference)
    {

        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
             new JsonResponse(['error' => 'order']);
        }



        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object = $entityManager->getRepository(Products::class)->findOneByName($product->getProduct());
            $product_for_stripe[]= [
                'price_data' => [

                    'currency' => 'eur',

                    'unit_amount' => $product->getPrice(),

                    'product_data' => [

                        'name' => $product->getProduct(),

                        'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],

                    ],

                ],

                'quantity' => $product->getQuantity(),
            ];
        }

        $product_for_stripe[]= [
            'price_data' => [

                'currency' => 'eur',

                'unit_amount' => $order->getCarrierPrice() * 100,

                'product_data' => [

                    'name' => $order->getCarrierName(),

                    'images' => [$YOUR_DOMAIN],

                ],

            ],

            'quantity' => 1,
        ];


        Stripe::setApiKey('sk_test_51J1QeFBed74eFDWvDo20gq4a9UksiWtaWw25a0FerqvCEsV7lATupwZRHSGowNttlQhmxEcG2i2YxVxfHq6POiY900jeBfUJCv');


        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],

            'line_items' => [$product_for_stripe],

            'mode' => 'payment',

            'success_url' => $YOUR_DOMAIN . '/commandes/merci/{CHECKOUT_SESSION_ID}',

            'cancel_url' => $YOUR_DOMAIN . '/commandes/erreur/{CHECKOUT_SESSION_ID}',

        ]);

        $order->setStripeSessionId($checkout_session->id);


        $response = new JsonResponse(['id' => $checkout_session->id]);

        $entityManager->flush();
        return $response;

    }
}
