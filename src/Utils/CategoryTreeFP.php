<?php

namespace App\Utils;
use App\Utils\AbstractClasses\CategoryTreeAbstract;


class CategoryTreeFP extends CategoryTreeAbstract {

    public function getCategoryListAndParent(int $id){

        $parentData = $this->getMainParent($id);
        $this->getMainParentName = $parentData['name'];
        $this->getMainParentId = $parentData['id'];
        $key = array_search($id, array_column($this->categoriesArrayFromDb, 'id'));
        $this->currentCategoryName = $this->categoriesArrayFromDb[$key]['name'];

        $category_array = $this->buildTree($parentData['id']);
        return $this->getCategoryList($category_array);
    }
    public function getCategoryList(array $category_array){

        $this->categorylist .= '<ul>';

        foreach ($category_array as $value){

            $catName = $value['name'];
            $url = $this->url->generate('items_list',['categoryname' => $value['name'],'id' => $value['id']]);
            $this->categorylist .= '<li>'.'<a href="'.$url.'">'.$catName.'</a>';

            if(!empty($value['children'])){
                $this->getCategoryList($value['children']);
            }
            $this->categorylist .= '</li>';
        }

        $this->categorylist .= '</ul>';
        return $this->categorylist;
    }

    public function getMainParent(int $id){

        $key = array_search($id, array_column($this->categoriesArrayFromDb, 'id'));

        if($this->categoriesArrayFromDb[$key]['parent_id'] != null){

            return $this->getMainParent($this->categoriesArrayFromDb[$key]['parent_id']);
        }else{
            return [
                'id'=>$this->categoriesArrayFromDb[$key]['id'],
                'name'=>$this->categoriesArrayFromDb[$key]['name']
        ];
        }
    }

    public function getChildIds(int $parent){
        static $ids = [];
        foreach ($this->categoriesArrayFromDb as $val){
            if($val['parent_id'] == $parent){
                $ids[] = $val['id'].',';
                $this->getChildIds($val['id']);
            }
        }
        return $ids;
    }

}