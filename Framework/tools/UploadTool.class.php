<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/14 0014
 * Time: 下午 2:05
 */

class UploadTool {

    private $allow_types;   //允许的类型
    private $allow_size;    //允许的大小

    private $error_info; //保存上传过程中的错误
    private $error_infos; //上传多个文件时 保存多个上传的错误信息

    public function __construct($allow_types='',$allow_size=''){
        $this->allow_types = $allow_types;
        $this->allow_size = empty($allow_size)?1024*1024*2:$allow_size;
    }

    /**
     * 将指定一个文件上传到$upload_path中
     * @param $fileinfo
     * @param $upload_path (目录)
     * @return bool
     */
    public function uploadOne($fileinfo,$upload_path){
        //拼出文件的上传目录
        $uploadDir = UPLOAD_PATH.$upload_path.'/'.date('Y-m-d');
        if(!is_dir($uploadDir)){
            mkdir($uploadDir,0777,true);//递归创建目录
        }

        //>>1.判断是否上传成功
        if($fileinfo['error']>UPLOAD_ERR_OK){
           $this->error_info = '上传过程中出错';
            return false;
        }
        //>>2.判断上传文件的类型是否符合
        if(!empty($this->allow_types) && !in_array($fileinfo['type'],$this->allow_types)){
            $this->error_info = '上传文件的类型不合法';
            return false;
        }
        //>>3.判断上传文件的大小是否满足条件
        if($fileinfo['size']>$this->allow_size){
            $this->error_info = '上传文件大小超出了限制..';
            return false;
        }

        //>>4. 移动上传文件
        if(is_uploaded_file($fileinfo['tmp_name'])){
            $new_filename  =   uniqid('goods_').'_'.date('YmdHis');
            $new_filename .=  strrchr($fileinfo['name'],'.');//获取后缀名

            move_uploaded_file($fileinfo['tmp_name'],$uploadDir.'/'."$new_filename");
            return 'Upload/'.$upload_path.'/'.date('Y-m-d').'/'.$new_filename;
        }
    }

    /**
     * 实现多文件上传
     * @param $fields
     * @param $upload_path
     * multiUpload($_FILES['img_url'])
     */
    public function multiUpload($fields,$upload_path){
        $fileinfos = array();
        foreach($fields['error'] as $k=>$error){
            if($error==0){ //成功
                $fileinfo = array();  //存放一个文件的信息
                $fileinfo['name'] =  $fields['name'][$k];
                $fileinfo['tmp_name'] = $fields['tmp_name'][$k];
                $fileinfo['type'] = $fields['type'][$k];
                $fileinfo['size'] = $fields['size'][$k];
                $fileinfo['error'] =  $fields['error'][$k];
                $fileinfos[] = $fileinfo;  //再将每一个文件信息 放到数据中
            }
        }

        $new_filenames = array();  //保存上传的文件名字
        /*
        foreach($fileinfos as $fileinfo){
            $new_filename  = $this->uploadOne($fileinfo,$upload_path);
            if($new_filename===false){
               $this->error_infos[]=$this->error_info;
            }else{
                $new_filenames[] = $new_filename;
            }
        }*/
        foreach($fileinfos as $fileinfo){
            $new_filename  = $this->uploadOne($fileinfo,$upload_path);
            if($new_filename===false){  //只要有一张图片上传失败..就返回false,不再继续上传
                 return false;
            }else{
                $new_filenames[] = $new_filename; //上传成功一张图片就保持到数组中
            }
        }
        return $new_filenames;  //只有全部上传成功才会返回所有上传后的地址..
    }



    /**
     * 只允许访问error_info成员变量
     * @param $name
     * @return mixed
     */
    public function __get($name){
        if($name=='error_info'){
            return $this->error_info;
        }
    }

    /**
     * 只能够给allow_types和allow_size设置值
     * @param $name
     * @param $value
     */
    public function __set($name,$value){
         if(in_array($name,array('allow_types','allow_size'))){
             $this->$name = $value;
         }
    }
}