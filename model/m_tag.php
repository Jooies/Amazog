<?php
class m_tag extends spModel{
    var $pk = "tid";
    var $table = "tag";
    
    var $verifier = array(
            "rules" => array(
                    "name" => array(
                            'notnull' => TRUE,
                    ),
            ),
            "messages" => array(
                    "name" => array(
                            'notnull' => "tag can't be null!",
                    ),
            ),
    );

    function check_repeat($name){
        $conditions = array("name"=>$name);
        if($this->find($conditions)){
            return False;//there is a repeat name in table
        }else{
            return True;
        }      
    }
}