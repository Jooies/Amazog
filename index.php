<?php
define("APP_PATH",dirname(__FILE__));
define("SP_PATH",dirname(__FILE__).'/system');
//判断是否有install.lock文件，没有的话则进入install.php
//进行安装
if(true != @file_exists(APP_PATH.'/config.php')){
    require(APP_PATH.'/install.php');
    exit;
}
function togetavatar($params)
{
        extract($params);
        $gravatar_id = md5($gravatar_id);
        return "http://www.gravatar.com/avatar/{$gravatar_id}?d=&s={$size}";
}
function content($params)
{
    $contents = $params['c']; // 文章内容
    $arr1 = explode('<!--more-->',$contents); 
    echo $arr1['0'];     
}
require("config.php");
require(SP_PATH."/SpeedPHP.php");
import('email.class.php');
spRun();