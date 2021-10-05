<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Item;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Service\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    public function index(OrderManager $orderManager): Response
    {
        // Je passe une commande avec
        // Cuve à gasoil x1
        // Nettoyant pour cuve x3
        // Piquet de clôture x5
        $product1 = new Product('Cuve à gasoil', 250000, new Brand(Brand::BRANDS_NAMES['Farmitoo']));
        $product2 = new Product('Nettoyant pour cuve', 5000, new Brand(Brand::BRANDS_NAMES['Farmitoo']));
        $product3 = new Product('Piquet de clôture', 1000, new Brand(Brand::BRANDS_NAMES['Gallagher']));

        $productsItems = [
            new Item($product1, 1),
            new Item($product2, 3),
            new Item($product3, 5)
        ];
        $promotion = new Promotion(50000, 8, false);

        $order = $orderManager->createOrder($productsItems, $promotion);

        return $this->render('displayCart.html.twig', [
            'order' => $order,
        ]);

    }

    /**
     * @param Request $request
     * @return Response
     */
    public function orderPayment(Request $request): Response
    {
        $amount = $request->query->get('amount');
        // $orderId = $request->query->get('orderId');
        // toDo : get the order from the Id and NOT amount
        // toDo : check that the order belongs to the connected customer
        // toDo : check that the order is not already payed
        // toDo : make them pay

        return $this->render('payment.html.twig', [
            'amount' => $amount,
        ]);

    }
}
