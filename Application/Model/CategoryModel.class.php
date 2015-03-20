<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoryModel
 *
 * @author Administrator
 */
class CategoryModel extends Model{
        
        public function getname($id){
                
               //>>1.根据类别的ID获取类别的名称
                $sql = 'select category_name from easy_category where category_id = '.$id;
                return $this->db->fetchColumn($sql);
                
        }
        
        
}
