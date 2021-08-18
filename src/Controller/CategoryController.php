<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\CategoryType;
use App\Entity\Category;
use App\Utils\CategoryTreeFP;
use App\Utils\CategoryTreeAdminList;
use App\Utils\CategoryTreeAdminOptionList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findBy(['parent'=> null],['name'=>'ASC']);

       
        return $this->render('category/index.html.twig', [
            'categories' => $category,
        ]);
    }

    public function main_category(){

        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy(['parent'=>null]);
        //dd($categories);
        return $this->render("category/_main_categories.html.twig",[
            'categories' => $categories
        ]);
    }
    public function menu_category(){

        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy(['parent'=>null]);
        //dd($categories);
        return $this->render("category/_menu_categories.html.twig",[
            'categories' => $categories
        ]);
    }    

    /**
     * @Route("/items_list/{id}/{page}", defaults={"page":1}, name="items_list")
     */
    public function items_list(CategoryTreeFP $catfp, $id, $page){
        
        $categories = $catfp->getCategoryListAndParent($id);
        $ids = $catfp->getChildIds($id);
        array_push($ids, $id);
        $items = $this->getDoctrine()->getRepository(Item::class)->findByChildIds($ids, $page);
        //dd($items);
        return $this->render('category/item_list.html.twig',[
            'subcategories' => $catfp,
            'items' => $items
        ]);
    }

    /**
     * @Route("/admin/categories", name="admin_category")
     */
    public function categories(CategoryTreeAdminList $catadmin, Request $request){
        
        $subcategories = $catadmin->buildTree();
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            //dd($form);
            $category_name = $request->request->get('category')['name'];
            $category->setName($category_name);
            $repo = $this->getDoctrine()->getRepository(Category::class);

            $parent = $repo->find($request->request->get('category')['parent']);

            $category->setParent($parent);
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute("admin_category");
            
        }
        return $this->render('admin/categories.html.twig',[
            'subcategories' => $catadmin->getCategoryList($subcategories),
            'form' => $form->createView()
        ]);
    } 
    
    /**
     * @Route("admin/edit-category/{id}", name="edit_category") 
     */
    public function edit_category(Request $request, Category $category){

        $form = $this->createForm(CategoryType::class, $category);
        // dd($form);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $category_name = $request->request->get('category')['name'];
            $category->setName($category_name);
            $repo = $this->getDoctrine()->getRepository(Category::class);

            $parent = $repo->find($request->request->get('category')['parent']);

            $category->setParent($parent);
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute("items_list");
            
        }        
        return $this->render('admin/edit_category.html.twig',[
            'category' => $category,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("admin/delete-category/{id}", name="delete_category") 
     */
    public function delete_category(Category $category){
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('admin_category');
    }

    public function getAllCategories(CategoryTreeAdminOptionList $categories, $editedCategory = null){

        $categories->getCategoryList($categories->buildTree());

        return $this->render('admin/_all_categories.html.twig',[
            'categories' => $categories,
            'editedCategory' => $editedCategory
        ]);
    }
}
