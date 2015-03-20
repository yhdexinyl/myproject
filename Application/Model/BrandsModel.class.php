<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BrandsModel
 *
 * @author Administrator
 */
class BrandsModel extends Model{

        public function getname($id){
                
        //>>1.根据品牌的ID获取品牌的名称
                $sql = 'select brands_name from easy_brands where brands_id = '.$id;
                return $this->db->fetchColumn($sql);
                
        }
        
        
        
}
