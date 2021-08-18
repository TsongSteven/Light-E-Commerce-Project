<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;
use Razorpay\Api\Api;
use App\Entity\Item;
use App\Entity\TopProd;
use App\Entity\Contact;
use App\Entity\CheckOutDetails;
use App\Entity\GuestUser;
use App\Entity\OrderProduct;
use App\Form\ItemType;
use App\Form\ContactType;
use App\Form\ChekoutType;
use App\Form\GuestUserType;
use App\Entity\FrontCategory;
// use App\Event\PdfCreatedEvent;
use App\Entity\SizeQtyAttributes;
use App\Form\SizeQtyAttrType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MainController extends AbstractController
{

    public function __construct(Environment $twig){

        $this->twig = $twig;
    }

    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        $item = new Item();
        $data = $this->getDoctrine()->getRepository(Item::class)->getItemsInDesc();
        $front_cat = $this->getDoctrine()->getRepository(FrontCategory::class)->findAll();
        return $this->render('main/index.html.twig',[
            'data' => $data,
            'front_cat' => $front_cat
        ]);
    }
    /**
     * @Route("/product/{id}", name="view_product")
     */
    public function viewProductHome($id, SessionInterface $session, Request $request){
        $product = $this->getDoctrine()->getRepository(Item::class)->find($id);
        $isInBasket = false;
        $session->set('product_id', $id);    
        $id = $session->get('product_id');
        if(!isset($id)){
            
            return $this->redirectToRoute('main');
        }
       // $product = $em->getRepository(Item::class)->find($id);
        $basket = $session->get('basket',[]);
        if($request->isMethod('POST')){
            $product_id = $request->get('size');
            $product_quanity = $request->get('quantity');
            
            $sizeQtyAttributes = $this->getDoctrine()->getRepository(SizeQtyAttributes::class)->find($product_id);
            // dd($sizeQtyAttributes->getNewPrice());
            $product_qty_price = $sizeQtyAttributes->getNewPrice() * $product_quanity;
            $product_qty_b_price = $sizeQtyAttributes->getNewPrice();
            // dd($product_qty_price);
            $basket[] =array(
                "items" => array($product->getId(),$product->getImage(), $product->getTitle(), $product_qty_price, $sizeQtyAttributes->getAttrName(),$product_qty_b_price, $product_quanity),
                );
                // dd($basket);
            // $size = array($product_size);
            // array_push($basket, $size);
            $session->set('basket', $basket);   
            $this->addFlash('cart_msg','Added to cart successfully!!.');  
            return $this->redirectToRoute('main');
        }
        //dump($product->getId());
        foreach($basket as $key => $value){
            //dd($value['items']);
            $isInBasket = in_array($product->getId(), $value['items']);  
        }
         
        // dd($isInBasket);
        return $this->render('main/view_product.html.twig',[
            'post' => $product,
            'isInBasket' => $isInBasket
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, EventDispatcherInterface $dispatch){
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $contact->setFlag('1');
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();    
        }
        return $this->render('main/contact.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/proceed", name="proceed", methods={"GET", "POST"})
     */
    public  function proceed(Request $request, SessionInterface $session){
        $em = $this->getDoctrine()->getManager();
        $product_size = $request->get('size');
        $size = $session->get('size');
        $id = $session->get('product_id');
        if(!isset($id)){
            
            return $this->redirectToRoute('main');
        }   
        

        $basket = $session->get('basket',[]);
        
        if($request->isMethod('POST')){
            unset($basket[$request->request->get('id')]);
            $session->set('basket', $basket);
        }

        $price = array_sum(array_map(function($product){
           return $product['items'][3];
        },
           $basket
        ));        

        // $product = $em->getRepository(Item::class)->find($id);    
        // $api = new Api('rzp_test_snXomEn9s8HBoJ', 'mYxntGDtlMaMnKddZ7efvomc');
        // $order = $api->order->create(array(
        //     'receipt' => '1',
        //     'amount' => 100,
        //     'currency' => 'INR'
        //     )
        //   );   

        //   $orderId = $order['id'];
        //   $session->set('size', $product_size);
        //   $session->set('order_id',$orderId);
        //   $session->set('name',$product->getTitle());
        //   $session->set('amount',$product->getPrice() * 100);
        $guest = new GuestUser();
        $op = new OrderProduct();
        $item = new Item();
        $form = $this->createForm(GuestUserType::class, $guest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $guest->setOrderStatus("process");
            $em = $this->getDoctrine()->getManager();            
            foreach($basket as $item_basket){
                // dd($item_basket['items'][6]);
                $op = new OrderProduct();
                $item = $this->getDoctrine()->getRepository(Item::class)->find($item_basket['items'][0]);
                $guest->addOrderproduct($op->setSize($item_basket['items'][4]));
                $guest->addOrderproduct($op->setPrice($item_basket['items'][3]));
                $guest->addOrderProduct($op->setItemIds($item_basket['items'][0]));
                $guest->addOrderProduct($op->setProductNo($item_basket['items'][6]));
                $guest->addOrderProduct($op->setItem($item));
                $em->persist($op);
            }
            $em->persist($guest);
            $em->flush();
            $session->set('id',$id);
            $session->set('guestnameid',$guest->getId());
        return $this->redirectToRoute("cash-on-delivery"); 
        }
            
        return $this->render('main/proceedtopay.html.twig',[
            'form' => $form->createView(),
            // 'product' => $product,
            // 'size' => $product_size,
            'price' => $price,
            'size' => $size
        ]);
    }

    /**
     * @Route("/cash-on-delivery", name="cash-on-delivery")
     */
    public function cod(SessionInterface $session){
        $id = $session->get('id');
        if(!isset($id)){
            
            return $this->redirectToRoute('main');
        }        
        $guestnameid_session = $session->get('guestnameid');    
        // dump($guestnameid_session);
        // die;
        $guest = new GuestUser();
        $guestData = $this->getDoctrine()->getRepository(GuestUser::class)->find($guestnameid_session);
        $guestData->setOrderConfirm(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($guestData);
        $em->flush();
        return $this->redirectToRoute("thank-you");
    }

    /**
     * @Route("/thank-you", name="thank-you")
     */
    public function thank_you(SessionInterface $session){
        $id = $session->get('id');
        if(!isset($id)){
            
            return $this->redirectToRoute('main');
        }      
        $session->clear();    
        return $this->render('main/thankyou.html.twig');
    }
    /**
     * @Route("/checkout/", name="checkout")
     */
    public function checkout(SessionInterface $session){
        $id = $session->get('id');

        if(!isset($id)){
            
            return $this->redirectToRoute('main');
        }
        $basket = $session->get('basket',[]);
        $price = array_sum(array_map(function($product){
            return $product['items'][3];
         },
            $basket
         ));   
        // $em = $this->getDoctrine()->getManager();
        // $product = $em->getRepository(Item::class)->find($id);

        return $this->render('main/checkout.html.twig',['price'=> $price]);
    }


    public function top_products(){
        
        $top_prod = $this->getDoctrine()->getRepository(TopProd::class)->findAll();
        return $this->render('main/top_products.html.twig',['top_prod' => $top_prod ]);
    }
    
    /**
     * @Route("/price" ,name="_price")
     */
    public function price(Request $request){
        if ($request->isXmlHttpRequest()) {
            $p_id = $request->request->get('p_id');
            $data = $this->getDoctrine()->getRepository(SizeQtyAttributes::class)->find($p_id);
            $price = array("new_price" => $data->getNewPrice(), "old_price" => $data->getOldPrice());
        }
        $response = new Response(json_encode($price));
        return $response;
    }

    /**
     * @Route("/get-all-products",name="et-all-products")
     */
    public function get_all_products(){
        $data = $this->getDoctrine()->getRepository(Item::class)->findAll();
        return $this->render('main/get_all_items.html.twig',[
            'items' => $data,
        ]);        
    }
}
