<?php

namespace App\Utils;
use App\Utils\AbstractClasses\CategoryTreeAbstract;

class CategoryTreeAdminList extends CategoryTreeAbstract {


    public function getCategoryList(array $category_array){

        $this->categorylist .= '<ul>';

        foreach ($category_array as $value){
            $url_edit = $this->url->generate('edit_category',['id' => $value['id']]);
            $url_delete = $this->url->generate('delete_category',['id'=> $value['id']]);
            $catName = $value['name'];
            $url = $this->url->generate('items_list',['id' => $value['id']]);
            $this->categorylist .= '<li>'.'<a class="font-weight-bold text-dark mr-1" href="'.$url.'">'.$catName.'</a>'.
            '<a href="'.$url_edit.'" class="font-weight-bold text-success mr-1">'.'Edit'.'</a>'.
            '<a href="'.$url_delete.'" class="font-weight-bold text-danger mr-1">'.'Delete'.'</a>'.
            '</li>';

            if(!empty($value['children'])){
                $this->getCategoryList($value['children']);
            }
        }

        $this->categorylist .= '</ul>';
        return $this->categorylist;        
    }
}