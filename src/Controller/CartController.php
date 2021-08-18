<?php

namespace App\Controller;

use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function cart(Request $request, SessionInterface $session): Response
    {

        $basket = $session->get('basket',[]);
        // foreach($basket as $key => $value){
        //     $newp = $value['items'][5] *2;
        //     $basket[$key]['items'][3] = $newp;
        //     $session->set('basket', $basket);
        // }        
        $size = $session->get('size');
        if($request->isMethod('POST')){
            
           $id = $request->request->get('0');
           foreach($basket as $key => $value){
               if($value['items'][0] == $id){
                unset($basket[$key]);
                $session->set('basket',$basket);
               }
           }

        }
        if(empty( $session->get('basket',[]))){
            $price= 0;
        }else{
            $price = array_sum(array_map(function($product){
                return $product['items'][3];
             },
                 $basket
             ));
        }
        
       // dd($price);
        return $this->render('cart/cart.html.twig',[
            'basket' => $basket,
            'price' => $price,
            // 'size' => $size
        ]);
    }
        /**
     * @Route("/_cart_price/{id}", name="_cart_price")
     */
    public function _cart_price(Request $request, SessionInterface $session, $id): Response
    {

        $basket = $session->get('basket',[]);
        if ($request->isXmlHttpRequest()) {
        $qty = $request->request->get('qty');
         foreach($basket as $key => $value){
            if($value['items'][0] == $id){
             $newp = $value['items'][5] * $qty;
             $basket[$key]['items'][3] = $newp;
             $basket[$key]['items'][6] = $qty;
             $session->set('basket', $basket);
             $response = new Response(json_encode($value['items'][3]));
             return $response;  
            }
            
         }
          
        }

    }

    public function countCartItems( SessionInterface $session){
        $basket = $session->get('basket',[]);
        
        return $this->render('cart/count_cart.html.twig',[
            'c_items' => count($basket)
        ]);
    }
}
