<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoryController
 *
 * @author Administrator
 */
class CategoryController extends Controller{

        /**
         * 展示商品类别及其下面的商品列表
         */
       public function indexAction(){
               
               //>>1.获取商品类别数据
               $CategoryModel = new CategoryModel();
               $Categorys = $CategoryModel ->getAll();
      
            //>>2.获取商品类别的ID, 当前页数，分页数
               $CategoryId = isset($_GET['CategoryId'])?$_GET['CategoryId']:1;
               $page = isset($_GET['page'])?$_GET['page']:1;
               $pagesize = isset($_GET['pagesize'])?$_GET['pagesize']:6;
               
          /**
          * 展示商品列表数据
          */
               $GoodsModel = new GoodsModel();
               $Goods = $GoodsModel->getPageResult("category_id ={$CategoryId}",$page,$pagesize);
               
               
               /**
                * 对商品页面进行分页
                */
                $url = "index.php?p=Home&c=Category&a=index&CategoryId=".$CategoryId;
                $PageTool = new PageTool();
                $total_page = ceil($Goods['count']/$pagesize);
                $nest_page = ($page+1)<$total_page?$page+1:$total_page;
               
               
                $this->assign('Categorys',$Categorys);
                $this->assign('Goods',$Goods['rows']);
                $this->assign('url',$url);
                $this->assign('count',$Goods['count']);
                $this->assign('page',$page);
                $this->assign('total_page',$total_page);
                $this->assign('nest_page',$nest_page);
                $this->display('index.html');
                
       }
       
       
       
        
}
