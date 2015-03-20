<?php


class messageModel extends Model{
    
    //传入数据库
    public function insert($msg_type, $msg_title,$msg_content){
//        var_dump( $msg_type, $msg_type,$msg_content);exit;
    $row = "insert into user_message values (null,'$msg_type','$msg_title','$msg_content')";
    $result = $this->db->query($row);
    if($result){
        return 1;
    }
    }
}
