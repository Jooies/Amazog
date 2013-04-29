<?php
class setup extends spController{
    function index(){
    $setupObj = spClass("m_setup");
    // 寻找数据
    $title = $setupObj->find(array("skey"=>'title'));
    $keywords = $setupObj->find(array("skey"=>'metakeywords'));
    $description = $setupObj->find(array("skey"=>'metadescription'));
    $announcement = $setupObj->find(array("skey"=>'announcement'));
    $email = $setupObj->find(array("skey"=>'email'));
    $main_count = $setupObj->find(array("skey"=>'main_count'));
    $siderbar_post = $setupObj->find(array("skey"=>'siderbar_post'));
    $siderbar_comments = $setupObj->find(array("skey"=>'siderbar_comments'));
    $smtpserver = $setupObj->find(array("skey"=>'smtpserver'));
    $smtpserverport = $setupObj->find(array("skey"=>'smtpserverport'));
    $smtpuser = $setupObj->find(array("skey"=>'smtpuser'));
    $smtppass = $setupObj->find(array("skey"=>'smtppass'));
    $comments = $setupObj->find(array("skey"=>'comments'));
    $comments_mail = $setupObj->find(array("skey"=>'comments_mail'));
    $post_comments = $setupObj->find(array("skey"=>'post_comments'));
    $post_time = $setupObj->find(array("skey"=>'post_time'));
    $skin = $setupObj->find(array("skey"=>'skin'));
    // 给数据赋名称
    $this->title = $title['name'];
    $this->keywords = $keywords['name'];
    $this->description = $description['name'];
    $this->announcement = $announcement['name'];
    $this->email = $email['name'];
    $this->main_count = $main_count['name'];
    $this->siderbar_post = $siderbar_post['name'];
    $this->siderbar_comments = $siderbar_comments['name'];
    $this->smtpserver = $smtpserver['name'];
    $this->smtpserverport = $smtpserverport['name'];
    $this->smtpuser = $smtpuser['name'];
    $this->smtppass = $smtppass['name'];
    if($comments['name'] == 'true'){$this->comments = 'true';}
    if($comments_mail['name'] == 'true'){$this->comments_mail = 'true';}
    $this->post_comments = $post_comments['name'];
    $this->post_time = $post_time['name'];
    $this->skinname = $skin['name'];
    $this->skin = $setupObj->getDir('template/skin');
    $this->display("admin/setup.html");
    }

    function index_update(){
    $setupObj = spClass("m_setup");
    // 设置
    $title = array("skey"=>'title');
    $keywords = array("skey"=>'metakeywords');
    $description = array("skey"=>'metadescription');
    $announcement = array("skey"=>'announcement');
    $email = array("skey"=>'email');
    $main_count = $setupObj->find(array("skey"=>'main_count'));
    $siderbar_post = $setupObj->find(array("skey"=>'siderbar_post'));
    $siderbar_comments = $setupObj->find(array("skey"=>'siderbar_comments'));
    $smtpserver = $setupObj->find(array("skey"=>'smtpserver'));
    $smtpserverport = $setupObj->find(array("skey"=>'smtpserverport'));
    $smtpuser = $setupObj->find(array("skey"=>'smtpuser'));
    $smtppass = $setupObj->find(array("skey"=>'smtppass'));
    $comments = $setupObj->find(array("skey"=>'comments'));
    $comments_mail = $setupObj->find(array("skey"=>'comments_mail'));
    $post_comments = $setupObj->find(array("skey"=>'post_comments'));
    $post_time = $setupObj->find(array("skey"=>'post_time'));
    $skin = $setupObj->find(array("skey"=>'skin'));
    // 保存
    $setupObj->update($title, array("name"=>$this->spArgs("title")));
    $setupObj->update($keywords, array("name"=>$this->spArgs("keywords")));
    $setupObj->update($description, array("name"=>$this->spArgs("description")));
    $setupObj->update($announcement, array("name"=>$this->spArgs("n")));
    $setupObj->update($email, array("name"=>$this->spArgs("mail"))); 
    $setupObj->update($main_count, array("name"=>$this->spArgs("main_count"))); 
    $setupObj->update($siderbar_post, array("name"=>$this->spArgs("siderbar_post")));
    $setupObj->update($siderbar_comments, array("name"=>$this->spArgs("siderbar_comments")));
    $setupObj->update($smtpserver, array("name"=>$this->spArgs("smtpserver")));
    $setupObj->update($smtpserverport, array("name"=>$this->spArgs("smtpserverport")));
    $setupObj->update($smtpuser, array("name"=>$this->spArgs("smtpuser")));
    $setupObj->update($smtppass, array("name"=>$this->spArgs("smtppass")));
    $setupObj->update($comments, array("name"=>$this->spArgs("com")));
    $setupObj->update($comments_mail, array("name"=>$this->spArgs("email")));
    $setupObj->update($post_comments, array("name"=>$this->spArgs("post_comments")));
    $setupObj->update($post_time, array("name"=>$this->spArgs("post_time")));
    $setupObj->update($skin, array("name"=>$this->spArgs("skin")));
    //提示成功并返回
    $this->success("保存成功!",spUrl("setup","index"));
    }
}