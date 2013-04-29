<?php
// 定义当前目录
define("APP_PATH",dirname(__FILE__));
if(true == @file_exists(APP_PATH.'/config.php')){
    exit();
}

//默认的参数 
$defaults = array(
	
	"DB_HOST" => "localhost",
	"DB_USER" => "root",
	"DB_PASSWORD" => "",
	"DB_DBNAME" => "speedblog",
	"DB_PREFIX" => "",
				
	"USERNAME" => "admin",
	"NICKNAME" => "admin",
	"SEX" => 0,
	"EMAIL" => "",
	"PASSWORD" => ""
);

function ins_checkdblink($configs){//用获得的数据库参数来检验数据库是否可以正常了连接
	global $dblink,$err;
	$dblink = mysql_connect($configs['DB_HOST'], $configs['DB_USER'], $configs['DB_PASSWORD']);
	if(false == $dblink){$err = '无法链接网站数据库，请检查网站数据库设置！';return false;}
	if(! mysql_select_db($configs['DB_DBNAME'], $dblink)){$err = '无法选择网站数据库，请确定网站数据库名称正确！'; return false;}
	ins_query("SET NAMES UTF8");
	return true;
}

function ins_query($sql,$prefix = ""){// 本地数据库入库
	global $dblink,$err;
	$sqlarr = explode(";", $sql);
	foreach($sqlarr as $single){
		if( !empty($single) && strlen($single) > 5 ){
			$single = str_replace("\n",'',$single);
			$single = str_replace("#DBPREFIX#",$prefix,$single );
			if( !mysql_query($single, $dblink) ){$err = "数据库执行错误：".mysql_error();return false;}
		}
	}
}

function ins_registeruser($configs, $prefix = ""){//增加管理员用户
	global $dblink,$err,$adminsql;
	$password = md5($configs["PASSWORD"]);
	$ctime = date('Y-m-d H:i:s');
        $adminsql = "insert into `{$prefix}admin` (`id`,`username`,`password`) values(1,'{$configs["USERNAME"]}','{$password}');";
	//return $adminsql;
        return True;

}

function ins_writeconfig($configs){//生成配置文件
	$configex = file_get_contents(APP_PATH."/config_sample.php");
	foreach( $configs as $skey => $value ){
		$skey = "#".$skey."#";
		$configex = str_replace($skey, $value, $configex);
	}
	file_put_contents (APP_PATH."/config.php" ,$configex);
}

$sql = "
DROP TABLE IF EXISTS #DBPREFIX#acl;

DROP TABLE IF EXISTS #DBPREFIX#admin;

DROP TABLE IF EXISTS #DBPREFIX#category;

DROP TABLE IF EXISTS #DBPREFIX#post;

DROP TABLE IF EXISTS #DBPREFIX#tag;

CREATE TABLE IF NOT EXISTS `#DBPREFIX#acl` (
  `aclid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `controller` varchar(30) CHARACTER SET utf8 NOT NULL,
  `action` varchar(30) CHARACTER SET utf8 NOT NULL,
  `acl_name` varchar(30) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`aclid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `#DBPREFIX#acl` VALUES(1, '管理后台首页', 'admin', 'index', 'GBADMIN');
INSERT INTO `#DBPREFIX#acl` VALUES(5, '日志编辑', 'post', 'add', 'GBADMIN');
INSERT INTO `#DBPREFIX#acl` VALUES(6, '日志管理', 'post', 'admin', 'GBADMIN');
INSERT INTO `#DBPREFIX#acl` VALUES(8, '日志修改', 'post', 'modify', 'GBADMIN');
INSERT INTO `#DBPREFIX#acl` VALUES(9, '日志删除', 'post', 'delete', 'GBADMIN');
INSERT INTO `#DBPREFIX#acl` VALUES(10, '分类编辑', 'category', 'add', 'GBADMIN');
INSERT INTO `#DBPREFIX#acl` VALUES(11, '分类管理', 'category', 'admin', 'GBADMIN');
INSERT INTO `#DBPREFIX#acl` VALUES(12, '分类修改', 'category', 'modify', 'GBADMIN');
INSERT INTO `#DBPREFIX#acl` VALUES(13, '分类删除', 'category', 'delete', 'GBADMIN');
INSERT INTO `#DBPREFIX#acl` VALUES(14, '标签管理', 'category', 'admin', 'GBADMIN');
INSERT INTO `#DBPREFIX#acl` VALUES(15, '标签修改', 'category', 'modify', 'GBADMIN');
INSERT INTO `#DBPREFIX#acl` VALUES(16, '标签删除', 'category', 'delete', 'GBADMIN');

CREATE TABLE IF NOT EXISTS `#DBPREFIX#admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `password` varchar(60) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#DBPREFIX#category` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `#DBPREFIX#category` VALUES(1, 'pythond');
INSERT INTO `#DBPREFIX#category` VALUES(2, 'php');
INSERT INTO `#DBPREFIX#category` VALUES(3, 'mongodb');
INSERT INTO `#DBPREFIX#category` VALUES(4, 'test');
INSERT INTO `#DBPREFIX#category` VALUES(5, 'hello world');
INSERT INTO `#DBPREFIX#category` VALUES(6, 'add');
INSERT INTO `#DBPREFIX#category` VALUES(7, 'minus');

CREATE TABLE IF NOT EXISTS `#DBPREFIX#post` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `time` datetime NOT NULL,
  `cid` int(11) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `#DBPREFIX#post` VALUES(5, 'asfs', 'asfafasfsafsfsf', '2013-01-23 22:02:08', 1);
INSERT INTO `#DBPREFIX#post` VALUES(6, 'asfa', '<p>asfafasfsafsfsf</p>\r\n', '2013-01-24 19:17:14', 1);
INSERT INTO `#DBPREFIX#post` VALUES(7, 'test', '<p>testzetesfsafsjkfjslfj</p>\r\n', '2013-01-23 22:57:25', 1);
INSERT INTO `#DBPREFIX#post` VALUES(8, 'first', '<p>hello world!This is a tough web blog program!</p>\r\n', '2013-01-24 11:14:56', 3);
INSERT INTO `#DBPREFIX#post` VALUES(11, 'first paper', '<p>this is the first post of this blog!Add it just for fun!</p>\r\n', '2013-01-24 23:24:06', 2);


CREATE TABLE IF NOT EXISTS `#DBPREFIX#tag` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8 NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `#DBPREFIX#tag` VALUES(2, 'asdfa', 4);
INSERT INTO `#DBPREFIX#tag` VALUES(3, 'asdfa', 5);
INSERT INTO `#DBPREFIX#tag` VALUES(4, 'asd', 6);
INSERT INTO `#DBPREFIX#tag` VALUES(5, 'test', 7);
INSERT INTO `#DBPREFIX#tag` VALUES(6, 'test', 8);
INSERT INTO `#DBPREFIX#tag` VALUES(7, 'hello', 9);
INSERT INTO `#DBPREFIX#tag` VALUES(9, 'paper', 11);

";

if(empty($_GET['step']) || $_GET['step'] == 1){
    $tips = $defaults;
    require(APP_PATH.'/install/step1.html');
}else{
	// 第三步，验证资料，写入资料，完成安装
	$dblink = null;$err=null;$adminsql = null;
	while(1){
		// 检查本地数据库设置
		ins_checkdblink($_POST);if( null != $err )break;
		// 增加管理员用户
		ins_registeruser($_POST,$_POST["DB_PREFIX"]);if( null != $err )break;
		// 本地数据库入库
		$sql .= $adminsql;
		ins_query($sql,$_POST["DB_PREFIX"]);if( null != $err )break;
		// 改写本地配置文件
		ins_writeconfig($_POST);if( null != $err )break;
		break;
	}
        if( null != $err ){ // 有错误则覆盖
		$tips = array_merge($defaults, $_POST); // 显示原值或新值
		require(APP_PATH.'/install/step1.html');
	}else{
		require(APP_PATH.'/install/step2.html');
	}
}

