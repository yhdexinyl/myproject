<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/7 0007
 * Time: 下午 4:46
 */

class Controller {
    /**
     * 跳转的功能..
     * 1. 立即跳转
     * 2. 延时跳转(提示信息)
     * @param $url
     * @param string $msg
     * @param int $times
     */
    public static function redirect($url,$msg='',$times=0){
        if(!headers_sent()){
            //使用header实现
            if(!$times){
                //立即跳转
                header("Location: $url");
            }else{
                //延时跳转
                echo "<h1>".$msg."</h1>";  //输出错误信息
                header("Refresh: $times;$url");
            }
        }else{
            //使用js实现
            echo <<<JS
            <script type='text/javascript'>
                window.setTimeout(function(){
                    window.location.href="$url";
                },{$times}000)
            </script>
JS;
            if($times){
                echo "<h1>".$msg."</h1>";
            };
        }
        exit;  //因为已经跳转. 所以后面的代码不需要执行...
    }

    /**
     * 选择视图页面
     * @param $fileName
     */
    public function display($fileName){
        extract($this->data);  //将
        require CURRENT_VIEW_PATH.CONTROLLER_NAME.DS.$fileName;
    }

    private $data = array();
    /**
     * 该方法是用来为视图页面分配数据
     * @param $name
     * @param $value
     */
    public function assign($name,$value){   //rows = $rows
        $this->data[$name] = $value;
    }
}