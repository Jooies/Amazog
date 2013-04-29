<?php
class m_post extends spModel{
    var $pk = "pid";
    var $table = "post";
    
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
                            'notnull' => "title can't be null!",
                    ),
                    "content" => array(
                            'notnull' => "content can't be null!",
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