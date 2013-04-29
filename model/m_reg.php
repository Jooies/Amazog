<?php
class m_reg extends spModel{
    var $pk='id';
    var $table = 'admin';
    var $verifier = array(
            "rules" => array(
                    "username" => array(
                            'notnull' => TRUE,
                            'minlength' => 3,
                            'maxlength' => 16,
                    ),
                    'password' => array(
                            'notnull' => TRUE,  
                            'minlength' => 8,   
                            'maxlength' => 20,                
                    ),
                    'password2' => array(
                            'equalto' => 'password',               
                    ),  
                    'email' => array(
                            'notnull' => TRUE,
                            'email' => 'email',               
                    ),                       
            ),
            "messages" => array(
                    "username" => array(
                            'notnull' => "用户名不能为空",
                            'minlength' => "用户名不能小于 3 个字符",
                            'maxlength' => "用户名不能大于 16 个字符",
                    ),
                    "password" => array(
                            'notnull' => "密码不能为空",
                            'minlength' => "密码不能小于 8 个字符",
                            'minlength' => "密码不能大于 20 个字符",
                    ),
                    'password2' => array(
                            'equalto' => "两次输入的密码不同",                
                    ), 
                    'email' => array(
                            'notnull' => '邮箱不能为空',
                            'email' => 'E-mail格式输入错误',
                    ),
            ),
    );
    function check_repeat($username){
        $conditions = array("username"=>$username);
        if($this->find($conditions)){
            return False;//there is a repeat name in table
        }else{
            return True;
        }      
    }
}