<?php

// 定义当前目录
define("APP_PATH",dirname(__FILE__));
// 定义框架目录
define("SP_PATH",APP_PATH."/SpeedPHP");


// 通用的全局配置
$spConfig = array(
	"db" => array(
			'host' => 'localhost',
			'login' => 'root',
			'password' => '',
			'database' => 'J',
			'prefix' => 'J_'
	),
	'lang' => array( 
		'cn' => 'default', // 默认语言，这里英文为默认语言
		'cn' => APP_PATH."/lang/cn.php", // 中文
	),
	'view' => array(
		'enabled' => TRUE, // 开启视图
		'config' =>array(
			'template_dir' => APP_PATH.'/template', // 模板目录
			'compile_dir' => APP_PATH.'/tmp', // 编译目录
			'cache_dir' => APP_PATH.'/tmp', // 缓存目录
			'left_delimiter' => '<{',  // smarty左限定符
			'right_delimiter' => '}>', // smarty右限定符
		),
		'debugging' => FALSE,
	),
        'dispatcher_error' => "import(APP_PATH.'/404.html');exit();",
	'model_path' => APP_PATH.'/model', // 定义model类的路径
        'ext' => array( // 扩展设置
                'spAcl' => array( // acl扩展设置
                        'prompt' => array("lib_user", "acljump"),
                ),
                'spVerifyCode' => array(
                        'width' => 60,
                        'height' => 20,
                        'length' => 4,
                        'bgcolor' => '#FFFFFF',
                        'noisenum' => 50,
                        'fontsize' => 22,
                        'fontfile' => 'font.ttf',
                        'format' => 'gif',
                
                ),
        ),
        
        'launch' => array( 
                'router_prefilter' => array( 
                        array('spAcl','mincheck') // 开启强制的权限控制
                )
        ),
	'url' => array( // URL设置
		'url_path_info' => FALSE, // 是否使用path_info方式的URL
	),
);

date_default_timezone_set('PRC');
