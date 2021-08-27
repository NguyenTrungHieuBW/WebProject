<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use App\Form\CartFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @IsGranted("ROLE_USER")
 */

class CartController extends AbstractController
{    

    /**
     * @Route("/cart",  name="cart_list")
     */
    public function cart () {
        $carts = $this->getDoctrine()
                        ->getRepository(Cart::class)
                        ->findAll();
        return $this->render("cart/index.html.twig",[
            'carts' => $carts
        ]);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete")
     */
    public function deleteCart ($id) {
        $cart = $this->getDoctrine()
                       ->getRepository(Cart::class)
                       ->find($id);
     /* SQL: "DELETE FROM Customer WHERE id = '$id'" */
                       
        if ($cart == null) {
            $this->addFlash("Error", "Cart ID in invalid");
            return $this->redirectToRoute("cart_list");
        }

        $manager = $this->getDoctrine()
                        ->getManager();
        $manager->remove($cart);
        $manager->flush();
        $this->addFlash("Success", "Item has been removed from cart");
        return $this->redirectToRoute("cart_list");
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function createCart (Request $request, $id) {
        $product = $this->getDoctrine()
                        ->getRepository(Product::class)
                        ->find($id);
        $quantity = 1;
        $carts = $this->getDoctrine()
                        ->getRepository(Cart::class)
                        ->findAll();

        //$quantity = $carts->getQuantity();

        $productname = $product->getName();
        $productprice = $product->getPrice();
        
        $cart = new Cart();
        $userid = $this->getUser()->getId();
        $cart->setUserid($userid);
        $cart->setProductid($id);
        $cart->setProductname($productname);
        $cart->setQuantity($quantity);
        $cart->setPrice($productprice);

        $manager = $this->getDoctrine()
                        ->getManager();
        $manager->  persist($cart);
        $manager->flush();
        return $this->redirectToRoute("cart_list");
    /*
        $product= $this->getDoctrine()
                       ->getRepository(Product::class)
                       ->find($id);

        $cart = new Cart();
        $form = $this->createForm(CartFormType::class, $cart);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $cart->setProductid("$product");
            $manager = $this->getDoctrine()
                            ->getManager();
            $manager->persist($cart);
            $manager->flush();
            $this->addFlash("Success","Add to cart successfully !");
            return $this->redirectToRoute("cart_list");
        }

        return $this->render(
            "cart/create.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    */
    }
}
