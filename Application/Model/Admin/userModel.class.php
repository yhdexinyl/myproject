<?php


class userModel extends Model{
    public function checkLogin($username,$password) {
        $row = $this->getAll("username=$username and password==md5($password)");
        return $row;
    }
    
    
}