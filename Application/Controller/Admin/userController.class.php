<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class userController extends Controller{
    //接收用户的数据
    public function loginAction(){
           $this->display('user_login.html');
    }




    public function checkLoginAction(){
        $username = isset($_POST['username'])?$_POST['username']:false;
         $password = isset($_POST['password'])?$_POST['password']:false;
        $user = new userModel();
        $row= $user->getAll("username=$username and password==md5($password)");
        if($row){
            //成功候跳转至成功列表
       $this->display('user_login.html');
        }
    }
}