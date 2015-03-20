<?php

class messageController extends Controller{

    public function getListAction(){
        $this->display('user_message.html');
    }
    
    public function addAction(){
        //接收数据
       $msg_type = isset($_POST['msg_type'])?$_POST['msg_type']:null;
        $msg_title = isset($_POST['msg_title'])?$_POST['msg_title']:0;
        
       $msg_content = isset($_POST['msg_content'])?$_POST['msg_content']:0;
//       echo '<pre>';
//       var_dump($msg_type,$msg_title,$msg_content);exit;
       $row = new messageModel();
       $result = $row->insert($msg_type, $msg_title,$msg_content);
       if($result==1){
            $this->redirect('index.php?p=home&c=message&a=getlist','留言成功,2秒后返回留言页面',2);
        }
    }
}