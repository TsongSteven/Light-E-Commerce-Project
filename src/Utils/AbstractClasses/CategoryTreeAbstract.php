<?php

namespace App\Utils\AbstractClasses;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class CategoryTreeAbstract{

    protected static $dbconn;
    public $categoriesArrayFromDb;
    public $categorylist;
    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $url){

        $this->em = $em;
        $this->url = $url;
        $this->categoriesArrayFromDb = $this->getCategories();
    }

    abstract public function getCategoryList(array $category_array);

    public function buildTree(int $parent_id = null){

        $subcategory = [];
        foreach($this->categoriesArrayFromDb as $category){
            if($category['parent_id'] == $parent_id){
                $children = $this->buildTree($category['id']);

                if($children){
                    $category['children'] = $children;
                }
                $subcategory[] = $category;
            }
        }
        return $subcategory;
    }


    private function getCategories(){

        if(self::$dbconn){
            return self::$dbconn;
        }else{
            $conn = $this->em->getConnection();
            $sql = "SELECT *FROM category";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return self::$dbconn = $stmt->fetchAll();
        }
    }
}