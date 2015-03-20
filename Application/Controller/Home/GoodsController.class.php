<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GoodsController
 *
 * @author Administrator
 */
class GoodsController extends Controller{

        public function indexAction(){
                //>>1.接收数据
                $GoodId = $_GET['GoodId'];
                $_POST;
                
                
                //>>2.获取单个商品数据
                $GoodsModel = new GoodsModel();
                $Good = $GoodsModel->getByPk($GoodId);
                
                
                
                //>>3.获取该商品品牌数据
                $BrandsModel = new BrandsModel();
                $Good['brand_name'] = $BrandsModel->getname($Good['brand_id']);
                
                //>>4.获取手机类型数据
                $CategoryModel = new CategoryModel();
                $Good['category_name'] = $CategoryModel ->getname($Good['category_id']);

                
                $this->assign('Good', $Good);
                $this->display('index.html');
                
                
        }
        
        
        
        
}
