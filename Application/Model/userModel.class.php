<?php

class userModel extends Model{
    public $error_info;
    public function checkLogin($username,$password){
      
        $row = $this->getAll("username='$username' and password='$password'");
           
        if(!empty($row)){
              return 1;
         }else{
             return false;
         }
     }
     
     public function register($username,$email,$pwd,$rpwd){
         
         if($this->count("username='$username'")>0){
             $this->error_info='用户名已存在';
             return FALSE;
         }
         if(count(str_split("$pwd"))<6){
             $this->error_info='密码长度不得小于6位';
             return FALSE;
         }
         if($rpwd!=$pwd){
             $this->error_info='两次密码输入不同';
             return FALSE;
         }
      
             //传入数据库
         $row = "insert into easy_user values (null,'$username','$email','$pwd')";
         $result = $this->db->query($row); 
            if($result){
             return true;    
             }
     }
     
     
}
