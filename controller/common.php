<?php
// Date---------------------------------
$postObj = spClass("m_post");//获取最新Post列表
$pageObj = spClass("m_page");//获取最新Post列表
$categoryObj = spClass("m_category");
$tagObj = spClass("m_tag");//获取最新Tag列表
$commentsObj = spClass("m_comments");
$setupObj = spClass("m_setup");//调用设置数据库
$userObj = spClass("m_admin");
$title = $setupObj->find(array("skey"=>'title'));//标题
$keywords = $setupObj->find(array("skey"=>'metakeywords'));//关键词
$description = $setupObj->find(array("skey"=>'metadescription'));//描述
$siderbar_post = $setupObj->find(array("skey"=>'siderbar_post'));
$siderbar_comments = $setupObj->find(array("skey"=>'siderbar_comments'));
$announcement = $setupObj->find(array("skey"=>'announcement'));
$adminemail = $setupObj->find(array("skey"=>'email'));
$skin = $setupObj->find(array("skey"=>'skin'));
// Me---------------------------------
$user = $_COOKIE['userName'];
$email = array("username"=>$user);
$email = $userObj->find($email);
$website = $email['website'];
$email = $email['email'];
$avatar_me = "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d="."&s=20";
// Show---------------------------------
$this->avatar_me = $avatar_me;
$commentsnav = $commentsObj->findAll(null,"m_id desc",null,$siderbar_comments['name']);
$this->commentsnav = $commentsnav;
$this->postnav = $postObj->findAll(NULL,'time desc',null,$siderbar_post['name']);
$this->pagenav = spClass("m_page")->findAll();
$tagnav = $tagObj->findAll(NULL,'tid desc');
$this->tagnav = $tagnav;
$this->title = $title['name'];
$this->keywords = $keywords['name'];
$this->description = $description['name'];
$this->announcement = $announcement['name'];
$this->email = $adminemail['name'];
$this->tagsize = $tagnav['count'] + 12;
$this->categorynav = $categoryObj->findAll();//倒序显示
$this->username = $user;
$tpl = $this->spArgs("tpl", $skin['name']);
?>