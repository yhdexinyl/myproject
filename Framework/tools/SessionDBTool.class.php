<?php


/**
 * 该类将session数据保存到数据库中...
 */
class SessionDBTool {
   private $db;
   public function __construct(){
       //>>1.告知PHP,将session数据保存到数据库中采用以下几个方法
       session_set_save_handler(
            array($this,'open'),
            array($this,'close'),
            array($this,'read'),
            array($this,'write'),
            array($this,'destroy'),
            array($this,'gc')
       );

      //>>2.开启session
      @session_start();
   }
   public function  open($savePath,$sessionName){  //$savePath就是在php.ini中配置的session文件保存到路径,    $sessionName就是发送给浏览器cookie的名字
       $this->db = DB::getInstance($GLOBALS['config']['DB']);
   }
    public  function  close(){
    }
    public  function  read($sessionId){  //可以根据 $sessionid找到对应的数据文件
        //p('read....'.$sessionId);
        $sql = "select sess_data from session where sess_id='$sessionId'";

        $result =  $this->db->fetchColumn($sql);
        if($result){
            return $result;
        }else{
            return '';  //如果根据sessionId没有读取相关的session数据,一定要返回一个空字符串
        }
    }

    public  function  write($sessionId,$data){  //php代码执行完毕之后将$_SESSION中的数据序列化后写到文件或者是数据库中
        //on duplicate key 当主键重复..   update sess_data='$data'" 就执行修改.
        $sql = "insert into session values('$sessionId','$data',unix_timestamp()) on duplicate key update sess_data='$data',last_modified=unix_timestamp()";
        return $this->db->query($sql);
    }
    public  function  destroy($sessionId){  //根据session_id找到文件或者是数据库记录将其删除
        $sql = "delete from session where sess_id = '$sessionId'";
        return $this->db->query($sql);
    }
    public  function  gc($lifetime){  //垃圾回收机制
        //>>1.确定哪些数据时垃圾数据
        //   where last_modified+$lifetime< unix_timestamp()
        //>>2.再将其删除
        $sql = "delete from session where  last_modified+$lifetime< unix_timestamp()";
        return $this->db->query($sql);
    }

}