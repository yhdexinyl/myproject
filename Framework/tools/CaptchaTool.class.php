<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/12 0012
 * Time: 下午 4:22
 */

class CaptchaTool {

    /**
     * 生成随机字符串
     */
    private static function makeCode($length){
        $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
        $chars = str_shuffle($chars);
        $chars = substr($chars,0,$length);
        return $chars;
    }
    /**
     * 生成验证码
     */
    public static function generate($length=4){
        $randomString = self::makeCode($length);
        new SessionDBTool();
        $_SESSION['randomString'] = $randomString;
        //将随机字符串写到图片上
        //>>1. 确定图片路径
            $imagePath = TOOLS_PATH.'captcha'.DS.'captcha_bg'.mt_rand(1,5).'.jpg';
        //>>2. 使用该路径下的图片创建一个图片资源
            $image = imagecreatefromjpeg($imagePath);
            $imageSize = getimagesize($imagePath);
            $width = $imageSize[0];
            $height = $imageSize[1];
        //>>3. 画一个白色边框
            $white = imagecolorallocate($image,255,255,255);
            imagerectangle($image,0,0,$width-1,$height-1,$white);
        //>>4. 写文字到图片上
            imagestring($image,5,$width/3,$height/5,$randomString,$white);
//            echo 'xxx';
            //向图片上添加 混淆 线和点
            for($i=0;$i<100;++$i){
                $color = imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
                imagesetpixel($image,mt_rand(0,$width),mt_rand(0,$height),$color);
            }
          /*  for($i=0;$i<5;++$i) {
                $color = imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
                imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $color);
            }*/
        //>>5. 输出图片
            header('Content-type: image/jpeg');
            imagejpeg($image);
        //>>6. 关闭图片
            imagedestroy($image);
    }

    /**
     * 验证请求中的验证码
     * @param $captcha
     */
    public static function  check($captcha){
        new SessionDBTool();
        return strtolower($captcha)==strtolower($_SESSION['randomString']);
       /* if(strtolower($captcha)==strtolower($_SESSION['randomStr'])){
              return true;
        }else{
            return false;
        }*/
    }

}