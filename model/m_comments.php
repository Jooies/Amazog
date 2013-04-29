<?php
class m_comments extends spModel{
    var $pk = "m_id";
    var $table = "comments";

    var $verifier = array(
            "rules" => array(
                    "content" => array(
                            'notnull' => TRUE,
                            'minlength' => 3,
                            'maxlength' => 300,
                    ),
            ),
            "messages" => array(
                    "content" => array(
                            'notnull' => "输入的内容不能为空！",
                            'minlength' => "输入的内容不能少于三个中文！",
                            'maxlength' => "输入的内容不能多于三百个个中文！",
                    ),  
            ),
    );
}