<?php
class m_page extends spModel{
    var $pk = "pid";
    var $table = "page";
    
    var $verifier = array(
            "rules" => array(
                    "title" => array(
                            'notnull' => TRUE,
                    ),
                    "content" => array(
                            'notnull' => TRUE,
                    ),
            ),
            "messages" => array(
                    "title" => array(
                            'notnull' => "标题不能为空!",
                    ),
                    "content" => array(
                            'notnull' => "内容不能为空!",
                    ),  
            ),
    );
     
    function check_title_repeat($title){
        $conditions = array("title"=>$title);
        if($this->find($conditions)){
            return False;//there is a repeat name in table
        }else{
            return True;
        }
        
    }
}