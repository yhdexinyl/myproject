<?php
/**
 * 该文件是一个配置文件. 通常来存放 数据库的信息和网站的一些默认信息
 */
return array(
    'DB'=>array(  //DB对应数据库的相关配置
        'host'=>'localhost',
        'user'=>'root',
        'password'=>'123456',
        'port'=>'3306',
        'dbname'=>'myproject',
        'prefix'=>'my_'   //配置前缀
    ),
    'app'=>array(  //将来为项目提供默认值
        'default_platform'=>'Admin',
        'default_controller'=>'Student',
        'default_action'=>'list',
    ),
    //前后台的配置信息
    'Admin'=>array(),
    'Home'=>array()
);