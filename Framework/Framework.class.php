<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/9 0009
 * Time: 上午 9:44
 */

class Framework {

    public static function run(){
          self::initPath();
          self::initConfig();
          self::initRequest();
          self::initClassMapping();
          self::initAutoLoad();  //先告知PHP哪个方法是用来完成自动加载...
          self::dispache();//执行控制器中的方法

    }
    /**
     * 初始化项目中的路径
     */
    private static function initPath(){
        defined('DS')  or define('DS',DIRECTORY_SEPARATOR);
        defined('ROOT_PATH')  or define('ROOT_PATH',dirname($_SERVER['SCRIPT_FILENAME']).DS);//项目根目录的绝对路径
        defined('APP_PATH')  or define('APP_PATH',ROOT_PATH.'Application'.DS);//项目根目录的绝对路径
        //ctrl+shift+u
        defined('FRAMEWORK_PATH')  or define('FRAMEWORK_PATH',ROOT_PATH.'Framework'.DS);//框架文件夹的绝对路径
        defined('TOOLS_PATH')  or define('TOOLS_PATH',FRAMEWORK_PATH.'tools'.DS);//框架文件夹下tools的绝对路径
        defined('CONFIG_PATH')  or define('CONFIG_PATH',APP_PATH.'Config'.DS);//Config的绝对路径
        defined('CONTROLLER_PATH')  or define('CONTROLLER_PATH',APP_PATH.'Controller'.DS);//Controller的绝对路径
        defined('MODEL_PATH')  or define('MODEL_PATH',APP_PATH.'Model'.DS);//Model的绝对路径
        defined('VIEW_PATH')  or define('VIEW_PATH',APP_PATH.'View'.DS);//View的绝对路径
        defined('UPLOAD_PATH')  or define('UPLOAD_PATH',ROOT_PATH.'Upload'.DS);//Upload文件夹的绝对路径
    }

    /**
     * 初始化配置参数
     */
    private  static  function initConfig(){
        $GLOBALS['config'] = require CONFIG_PATH.'application.config.php';
    }

    /**
     * 初始化请求
     */
    private  static  function initRequest(){
//>>1.c是用户传入的控制器
        defined('PLATFORM_NAME') or define('PLATFORM_NAME',isset($_GET['p'])?$_GET['p']:$GLOBALS['config']['app']['default_platform']) ; //确定平台
        defined('CONTROLLER_NAME') or define('CONTROLLER_NAME',isset($_GET['c'])?$_GET['c']:$GLOBALS['config']['app']['default_controller']);  //确定控制器
        defined('ACTION_NAME') or define('ACTION_NAME',isset($_GET['a'])?$_GET['a']:$GLOBALS['config']['app']['default_action']);  //确定控制器中的方法
//require CONTROLLER_PATH."$p/{$c}Controller.class.php";

        defined('CURRENT_CONTROLLER_PATH') or define('CURRENT_CONTROLLER_PATH',CONTROLLER_PATH.PLATFORM_NAME.DS);
        defined('CURRENT_VIEW_PATH') or define('CURRENT_VIEW_PATH',VIEW_PATH.PLATFORM_NAME.DS);
    }


    /**
     * 分发(根据用户的请求执行对应的控制器中的方法)
     */
    private  static  function dispache(){
    //>>2.根据c拼出控制器的名字
        $controller_name = CONTROLLER_NAME.'Controller';
    //>>3.根据名字创建控制器对象
        $controller = new $controller_name();
    //>>4.根据a执行控制器的方法
        $action_name  = ACTION_NAME.'Action';
        $controller->$action_name();
    }

    /**
     * 初始化类的映射
     */
    private  static  function initClassMapping(){
        $GLOBALS['map'] = array(     //$map中存放的时特殊类和路径的映射
            'DB'=>TOOLS_PATH.'DB.class.php',
            'Model'=>FRAMEWORK_PATH.'Model.class.php',
            'Controller'=>FRAMEWORK_PATH.'Controller.class.php'
        );
    }

    /**
     * 告知PHP代码,再使用一个未加载的类时请调用userAutoLoad 这个方法执行..
     */
    private static function initAutoLoad(){
        spl_autoload_register("Framework::userAutoLoad");
    }


    /**
     * 自动加载类
     * @param $class_name
     */
    private  static  function userAutoLoad($class_name){
        if(array_key_exists($class_name,$GLOBALS['map'])){
            require $GLOBALS['map'][$class_name];
        }elseif(substr($class_name,-10)=='Controller'){
            require CURRENT_CONTROLLER_PATH.$class_name.'.class.php';
        }elseif(substr($class_name,-5)=='Model'){
            require MODEL_PATH.$class_name.'.class.php';
        }elseif(substr($class_name,-4)=='Tool'){
            require TOOLS_PATH.$class_name.'.class.php';
        }
    }



}