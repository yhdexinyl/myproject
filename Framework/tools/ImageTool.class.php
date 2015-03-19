<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/16 0016
 * Time: 上午 11:44
 */

class ImageTool {

      //以下的两个变量供thumb方法使用
       private $create_functions = array(
            'image/png' =>'imagecreatefrompng',
            'image/jpeg' =>'imagecreatefromjpeg',
            'image/gif' =>'imagecreatefromgif',
        );
       private $out_functions = array(
            'image/png' =>'imagepng',
            'image/jpeg' =>'imagejpeg',
            'image/gif' =>'imagegif',
        );

    private $error_info;

    /**
     * 根据传入的大图片以及目标大小生成小图片
     * @param $big_filename
     * @param $max_width     生成后的大小
     * @param $max_height
     *
     * type: 0  补白
     * type: 1  裁剪..
     */
    public function thumb($big_filename,$max_width,$max_height,$type=0){
           $big_filename = ROOT_PATH.$big_filename;
         //>>1.判断用户传入的大图片 是否存在..
            if(!file_exists($big_filename)){
                $this->error_info = '图片文件不存在...';
                 return false;
            }
        //>>2. 计算缩放比例
                $imageSize = getimagesize($big_filename);
                list($src_width,$src_height) = $imageSize;
                $mime_type = $imageSize['mime'];
                $scale =  max($src_width/$max_width,$src_height/$max_height);  //确定缩放比例



        //>>3.计算实际缩放后的大小
                $width = $src_width/$scale;
                $height = $src_height/$scale;

          $thumb = imagecreatetruecolor($max_width,$max_height);
          $create_function = $this->create_functions[$mime_type];
          $src =    $create_function($big_filename);
        switch($type){
            case 0:
                //补白
                $color = imagecolorallocate($thumb,255,255,255);
                imagefill($thumb,0,0,$color);
//           /bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
             imagecopyresampled($thumb,$src,($max_width-$width)/2,($max_height-$height)/2,0,0,$width,$height,$src_width,$src_height);
            case 1:
                //剪切..
        }
//            开始缩放图片

        //>>4.根据大图片的路径生成一个小图片的路径上.
            //D:/code/day8/src.png   >  D:/code/day8/src_small.png
            $path_array = pathinfo($big_filename);
            $small_path = $path_array['dirname'].'/'.$path_array['filename'].'_small.'.$path_array['extension'];

          $out_function = $this->out_functions[$mime_type];
          $out_function($thumb,$small_path);  //输出到小图片的路径上
        //>>5.返回小图片的路径

            return str_replace(ROOT_PATH,'',$small_path);
    }


    /**
     * 只允许访问error_info.
     * @param $name
     * @return mixed
     */
    public function __get($name){
        if($name=='error_info'){
            return $this->error_info;
        }
    }
}