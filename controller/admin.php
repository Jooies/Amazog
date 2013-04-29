<?php
class admin extends spController{
    function index(){
        // Date---------------------------------
        $setupObj = spClass("m_setup");
        // Show---------------------------------
        $this->title = $setupObj->find(array("skey"=>'title'));
        $this->display("admin/index.html");
    }
    
    function login(){
        // Date---------------------------------
        $userObj = spClass("m_admin");
        // Post---------------------------------
        if($username = $this->spArgs("username")){
            $password = $this->spArgs("password");
            $row = array("username"=>$username,"password"=>$password);
            $result = $userObj->spVerifier($row);//数据验证         
            if(false == $result){                      
                if(false == $userObj->adminlogin($username,$password)){
                    $this->error("登陆失败！原因：权限不足！",spUrl("admin","login"));
                }else{
                    spClass("spAcl")->set('GBADMIN');//give admin the GBADMIN authority
                    $value = $username;
                    setcookie("userName", $value, time()+360000);
                    $this->success("登陆成功！",spUrl("admin","index"));
                }
            }else{
                foreach($result as $item){
                    foreach($item as $msg){
                        $this->error($msg,spUrl("admin","login"));
                    }
                }
            }
        }
        // Show---------------------------------
        $this->display("admin/login.html");
    }
    
    function logout(){//管理员注销
        // Post---------------------------------
        spClass("spAcl")->set("");//取消权限
        setcookie("userName", '');
        $this->success("注销成功!",spUrl("admin","login"));
    }
    
    function top(){//头部框架
        $setupObj = spClass("m_setup");
        $this->title = $setupObj->find(array("skey"=>'title'));
        $this->display("admin/top.html");
    }
    
    function menu(){
        $this->display("admin/menu.html");
    }
    
    function main(){
        $this->display("admin/main.html");
    }

    function user(){
        $userObj = spClass("m_admin");
        $this->user = $userObj->spPager($this->spArgs('page', 1), 5)->findAll(null,'id desc');
        $this->pager = $userObj->spPager()->getPager();
        $this->display("admin/user/admin.html");
    }

/*
 *
 *
 *
 *
 *
 * later function 
    function add(){
        //add user function 
    }
    
    function modify(){
        //modify user function 
    }
    
    function delete(){
        //delete user function
    }
*/
}