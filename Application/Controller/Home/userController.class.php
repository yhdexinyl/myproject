<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class userController extends Controller{
    
    public function loginAction(){
           $this->display('user_login.html');
    }
    
    

    /*
     * 注册用户名
     * 
     */
    public function registerAction(){
        $username =isset($_POST['username'])?$_POST['username']:null;
        $email =isset($_POST['email'])?$_POST['email']:null;
        $pwd = isset($_POST['pwd'])?$_POST['pwd']:null;
        $rpwd = isset($_POST['rpwd'])?$_POST['rpwd']:null;
        $userModel = new userModel();
        $result = $userModel->register($username,$email,$pwd,$rpwd);
        if($result===true){
            $this->redirect('index.php?p=home&c=user&a=login','注册成功,2秒过后跳转至登录页面',2);
        }
         elseif($result===false){
            $this->redirect('index.php?p=home&c=user&a=login',$userModel->error_info,2);
        }
       
        
    }





       /*
        * 登录
        */


    public function checkLoginAction(){
        $username = isset($_POST['username'])?$_POST['username']:false;
        $password = isset($_POST['password'])?$_POST['password']:false;
        
        $user = new userModel();
        $result= $user->checkLogin($username, $password);
        
        if($result==1){
            //成功跳转至成功列表
       $this->display('user_logsucc.html');
        }  else {
            //失败跳转至失败列表
            $this->display('user_logerr.html');
        }
    }
}