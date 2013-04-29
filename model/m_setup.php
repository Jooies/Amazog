<?php
class m_setup extends spModel{
    var $pk = "skey";
    var $table = "settings";

	var $verifier = array(
		"rules" => array( // 规则
			'title' => array(  // 这里是对Title的验证规则
				'notnull' => TRUE, // username不能为空
			),
			'keywords' => array(   // 这里是对关键词的验证规则
				'notnull' => TRUE, // 关键词不能为空
			),
			'description' => array(   // 这里是对描述的验证规则
				'notnull' => TRUE, // 描述不能为空
			),
		),
		"messages" => array( // 提示信息
			'title' => array(
				'notnull' => "输入的内容不能为空！",
				),
			'keywords' => array(
				'notnull' => "输入的内容不能为空！",
				),
			'description' => array(
				'notnull' => "输入的内容不能为空！",
			    ),
		),
	);
	function getDir($dir) {//用来获取文件夹
    $i = 0;
    if (false != ($handle = opendir ( $dir ))) {
        while ( false !== ($file = readdir ( $handle )) ) {
            if ($file != "." && $file != ".."&&!strpos($file,".")&&file_exists($dir."/".mb_convert_encoding($file,"utf-8","gbk")."/info.txt")) {
                $dirArray[mb_convert_encoding($file,"utf-8","gbk")]=mb_convert_encoding(file_get_contents($dir."/".mb_convert_encoding($file,"utf-8","gbk")."/info.txt"),"utf-8","gbk");
                $i++;
            }
        }
        closedir ( $handle );
    }
    return $dirArray;
}


}