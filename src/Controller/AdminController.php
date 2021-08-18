<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Size;
use App\Entity\GuestUser;
use App\Entity\Contact;
use App\Entity\Category;
use App\Entity\TopProd;
use App\Entity\OrderProduct;
use App\Form\ItemType;
use App\Form\ItemUpdateType;
use App\Form\TopProdType;
use App\Entity\SizeQtyAttributes;
use App\Form\SizeQtyAttrType;
use App\Entity\FrontCategory;
use App\Form\FrontendfeaturedproductsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use App\Utils\CategoryTreeFP;
use App\Utils\CategoryTreeAdminList;
use App\Utils\CategoryTreeAdminOptionList;
use Knp\Component\Pager\PaginatorInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {

        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/all-products", name="all_products")
     */
    public function all_products(Request $request, SluggerInterface $slugger){
        $item = new Item();   
        $products = $this->getDoctrine()->getRepository(Item::class)->findAll();
        return $this->render('admin/all_products.html.twig',[

            'products' => $products,
        ]);
    }
    /**
     * @Route("/admin/post-product", name="post_product")
     */
    public function postProduct(Request $request, SluggerInterface $slugger, CategoryTreeAdminOptionList $categories): Response
    {
        
        $item = new Item();   
        $form = $this->createForm(ItemType::class, $item);
        $categories->getCategoryList($categories->buildTree());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
        //   dd($form);
            $item = $form->getData();
            $item_category = $this->getDoctrine()->getRepository(Category::class)->find($request->request->get('item_category'));
            //dd($item->getSizeQtyAttr());
       
            $file_image = $form->get('image')->getData();
            if($file_image){
                $originalFileName = pathinfo($file_image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$file_image->guessExtension();
                $file_image->move($this->getParameter('saved_images'),$newFileName);
                $item->setImage($newFileName);
            }
            $item->setCategory($item_category);
            $em = $this->getDoctrine()->getManager();
            foreach($item->getSizeQtyAttr() as $sq){
 
                $item->addSizeQtyAttr($sq);
            }    
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('post_product');
        }
        return $this->render('admin/post-product.html.twig',[

            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }  
    
    /**
     * @Route("/admin/edit_post/{id}", name="edit_post")
     */
    public function editPost($id, Request $request, SluggerInterface $slugger, CategoryTreeAdminOptionList $categories){
        $item = new Item(); 
        $categories->getCategoryList($categories->buildTree());
        $data = $this->getDoctrine()->getRepository(Item::class)->find($id);
        $form = $this->createForm(ItemUpdateType::class, $data);
        $form->handleRequest($request);
        // dd($form);
        if($form->isSubmitted() && $form->isValid()){

            $item = $form->getData();
            $item_category = $this->getDoctrine()->getRepository(Category::class)->find($request->request->get('item_category'));
            //dd($item->getSizeQtyAttr());
            foreach($item->getSizeQtyAttr() as $sq){
 
                //dd($sq);
            }               
            $file_image = $form->get('image')->getData();
            if($file_image){
                $originalFileName = pathinfo($file_image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$file_image->guessExtension();
                $file_image->move($this->getParameter('saved_images'),$newFileName);
                $item->setImage($newFileName);
            }
            $item->setCategory($item_category);
            $em = $this->getDoctrine()->getManager();
            foreach($item->getSizeQtyAttr() as $sq){
 
                $item->addSizeQtyAttr($sq);
            }    
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('post_product');
        }
        return $this->render('admin/edit-product.html.twig',[

            'form' => $form->createView(),
            'categories' => $categories
        ]);

    }
    /**
     * @Route("/admin/delete_post/{id}", name="delete_post")
     */
    public function deletePost(Request $request, $id){
        $item = new Item(); 
        $post = $this->getDoctrine()->getRepository(Item::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('notice', 'Removed Post Successfully!!');

        return $this->redirectToRoute("admin");
    }

    public function countMessage(){

        $em = $this->getDoctrine()->getManager();
        $message_count =  $em->getRepository(Contact::class)->countMail();

        return $this->render('admin/count_mail.html.twig',[
            'count' => $message_count
         ]);
    }

    public function countProductEnq(){

        $em = $this->getDoctrine()->getManager();
        $count_prod_enq =  $em->getRepository(GuestUser::class)->findBy(['OrderStatus' => 'process']);
        // dd($count_prod_enq);
        
        return $this->render('admin/count_prod_enq.html.twig',[
            'count' => count($count_prod_enq)
         ]);
    }

    /**
     * @Route("/admin/message", name="messages")
     */
    public function messages(){
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(Contact::class)->findAll();

        return $this->render('admin/messages.html.twig',[
            'messages' => $messages
        ]);
    }
    /**
     * @Route("admin/delet-message/{id}", name="delete_message")
     */
    public function delete_message($id){
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository(Contact::class)->find($id);
        $em->remove($message);
        $em->flush();
        $this->addFlash('notice','Delted Successfully!!');
        return $this->redirectToRoute('messages');
    }
    /**
     * @Route("/admim/view_message/{id}", name="view_message")
     */
    public function view_message($id){
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository(Contact::class)->find($id);
    }
    
    /**
     * @Route("/admin/order_product", name="order_product")
     */
    public function order_product(PaginatorInterface $paginator, Request $request){
        $guest = new GuestUser();
        
        //$orders = $this->getDoctrine()->getRepository(GuestUser::class)->findAll();
        $dql   = "SELECT a FROM App:GuestUser a ORDER BY a.id DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
    
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->render('admin/orders.html.twig',[
            'orders' => $pagination
        ]);
    }
    /**
     * @Route("/admin/view_order/{id}", name="view_order")
     */
    public function view_order($id, Request $request){
        $guest = new GuestUser();
        $orders_id = $this->getDoctrine()->getRepository(GuestUser::class)->find($id);
        $op = $orders_id->getOrderProduct();
        $em = $this->getDoctrine()->getManager();
        foreach($op as $item){
            $idm = $item->getItemIds();
            $product[] =  $this->getDoctrine()->getRepository(Item::class)->find($idm);  
            //$order_product = $this->getDoctrine()->getRepository(Item::class)->findOrderProduct(($idm));
        }

        if($request->isMethod('POST')){
            //dd($request->request->get('confirm_stat'));
            $product_stats = $request->request->get('confirm_stat');
            $up_date_orders = $this->getDoctrine()->getRepository(GuestUser::class)->find($id);
            //dd($orders_id);
            $em = $this->getDoctrine()->getManager();
            if($product_stats == 'confirmed'){
                //dd($product_stats);
                $up_date_orders->setOrderStatus('confirmed');
                $this->addFlash('notice','The Order was Confirmed...');
            }else{
                $up_date_orders->setOrderStatus('cancelled');
                $this->addFlash('notice','The Order was Cancelled...');
               
            }
            $em->flush();
            return $this->redirectToRoute('order_product');
        }
        return $this->render('admin/view_order.html.twig',[
            'order' => $orders_id,
            'orders' => $orders_id,
            'products' => $product
        ]);
    }

    public function countProcess(){
        $em = $this->getDoctrine()->getManager();
        $count_prod_confirmed =  $em->getRepository(GuestUser::class)->findBy(['OrderStatus' => 'confirmed']);

        return $this->render('cart/confirm_count.html.twig',[
            'count_prod_confirmed' =>count( $count_prod_confirmed)
        ]);
    }
    public function countPost(){
        $em = $this->getDoctrine()->getManager();
        $count_prod_post =  $em->getRepository(Item::class)->findAll();

        return $this->render('admin/product_count.html.twig',[
            'count_prod_post' =>count($count_prod_post)
        ]);
    }
    
    
    /**
     * @Route("/admin/front-featured-products", name="front-featured-products")
     */
    public function front_featured_products(Request $request, SluggerInterface $slugger, CategoryTreeAdminOptionList $categories){
        $frontCat = new FrontCategory();
        $categories->getCategoryList($categories->buildTree());
        $form_front_cat = $this->createForm(FrontendfeaturedproductsType::class, $frontCat);
        $front_cat = $this->getDoctrine()->getRepository(FrontCategory::class)->findAll();
        if($form_front_cat->handleRequest($request)->isSubmitted()){
            $frontCat = $form_front_cat->getData();
            $item_category = $this->getDoctrine()->getRepository(Category::class)->find($request->request->get('item_category'));           
            $file_image = $form_front_cat->get('image')->getData();
            if($file_image){
                $originalFileName = pathinfo($file_image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$file_image->guessExtension();
                $file_image->move($this->getParameter('saved_images'),$newFileName);
                $frontCat->setImage($newFileName);
            }    
            $frontCat->setCategory($item_category);
            $em = $this->getDoctrine()->getManager(); 
            $em->persist($frontCat);
            $em->flush();

            return $this->redirectToRoute('front-featured-products');                    
        }

        $front_top_prod = new TopProd();
        $form_front_top_prod = $this->createForm(TopProdType::class, $front_top_prod);
        $front_top_prod = $this->getDoctrine()->getRepository(TopProd::class)->findAll();       
        if($form_front_top_prod->handleRequest($request)->isSubmitted()){
            $front_top_prod = $form_front_top_prod->getData();
            $item_category = $this->getDoctrine()->getRepository(Category::class)->find($request->request->get('item_category'));           
            $file_image = $form_front_top_prod->get('image')->getData();
            if($file_image){
                $originalFileName = pathinfo($file_image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$file_image->guessExtension();
                $file_image->move($this->getParameter('saved_images'),$newFileName);
                $front_top_prod->setImage($newFileName);
            }    
            $front_top_prod->setCategory($item_category);
            $em = $this->getDoctrine()->getManager(); 
            $em->persist($front_top_prod);
            $em->flush();

            return $this->redirectToRoute('front-featured-products');                    
        }
        
        return $this->render('admin/frontend_featured_products.html.twig',[
            'form_front_cat' => $form_front_cat->createView(),
            'form_front_top_prod' => $form_front_top_prod->createView(),
            'categories' => $categories,
            'front_cat' => $front_cat,
            'front_top_prod' => $front_top_prod
        ]);
    }
 
    public function postTopCat(Request $request, SluggerInterface $slugger, CategoryTreeAdminOptionList $categories){
        $frontCat = new FrontCategory();
        $categories->getCategoryList($categories->buildTree());
        $form = $this->createForm(FrontendfeaturedproductsType::class, $frontCat);
        $front_cat = $this->getDoctrine()->getRepository(FrontCategory::class)->findAll();

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $frontCat = $form->getData();
            $item_category = $this->getDoctrine()->getRepository(Category::class)->find($request->request->get('item_category'));           
            $file_image = $form->get('image')->getData();
            if($file_image){
                $originalFileName = pathinfo($file_image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$file_image->guessExtension();
                $file_image->move($this->getParameter('saved_images'),$newFileName);
                $frontCat->setImage($newFileName);
            }    
            $frontCat->setCategory($item_category);
            $em = $this->getDoctrine()->getManager(); 
            $em->persist($frontCat);
            $em->flush();

            return $this->redirectToRoute('front-featured-category');                    
        }

        return $this->render('admin/frontendfeaturedproducts.html.twig',[
            'form' => $form->createView(),
            'categories' => $categories,
            'front_cat' => $front_cat
        ]);
    }

    public function postTopProd(Request $request, SluggerInterface $slugger, CategoryTreeAdminOptionList $categories){
        $front_top_prod = new TopProd();
        $categories->getCategoryList($categories->buildTree());
        $form = $this->createForm(TopProdType::class, $front_top_prod);
        $front_cat = $this->getDoctrine()->getRepository(TopProd::class)->findAll();        
        $form->handleRequest($request);
        if($form->isSubmitted()){

            dd('here');
            $frontCat = $form->getData();
            $item_category = $this->getDoctrine()->getRepository(Category::class)->find($request->request->get('item_category'));           
            $file_image = $form->get('image')->getData();
            if($file_image){
                $originalFileName = pathinfo($file_image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$file_image->guessExtension();
                $file_image->move($this->getParameter('saved_images'),$newFileName);
                $frontCat->setImage($newFileName);
            }    
            $frontCat->setCategory($item_category);
            $em = $this->getDoctrine()->getManager(); 
            $em->persist($frontCat);
            $em->flush();

            return $this->redirectToRoute('front-featured-category');                    
        }
        return $this->render('admin/frontendtopproducts.html.twig',[
            'form' => $form->createView(),
            'categories' => $categories,
            'front_cat' => $front_cat
        ]);        
    }
    // /**
    //  * @Route("/admin/edit-topcat-admin/{id}", name="topCatEdit")
    //  */
    // public function topCatEdit($id){
    //     $frontcat_edit = $this->getDoctrine()->getRepository(FrontCategory::class)->find($id);
    // }

    /**
     * @Route("/admin/delete-topcat-admin/{id}", name="top_cat_delete")
     */
    public function topCatDelete($id){
        $frontcat_edit = $this->getDoctrine()->getRepository(FrontCategory::class)->find($id);
        $em = $this->getDoctrine()->getManager(); 
        $em->remove($frontcat_edit);
        $em->flush();
        return $this->redirectToRoute('front-featured-products');
    }

    /**
     * @Route("/admin/test-admin")
     */
     public function testAdmin(){
        $em = $this->getDoctrine()->getManager();
        $count_prod_enq =  $em->getRepository(GuestUser::class)->findBy(['OrderStatus' => 'process']);
        dd(count($count_prod_enq));
     }
}
