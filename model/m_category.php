<?php
class m_category extends spModel{
    var $pk = "cid";
    var $table = "category";
    
    var $verifier = array(
            "rules" => array(
                    "name" => array(
                            'notnull' => TRUE,
                            'minlength' => 2,
                            'maxlength' => 20,
                    ),                          
            ),
            "messages" => array(
                    "name" => array(
                            'notnull' => "category name can't be blank",
                            'minlength' => "category name is too short",
                            'maxlength' => "category name is too long",
                    ),  
            ),
    );
     
    function check_repeat($name,$oldname){
        $conditions = array("name"=>$name);
        if($oldname !== $name){
            if($this->find($conditions)){
            return False;}
            else{
                return True;
            }
        }
        else{
            return True;
        }      
    }
    
}