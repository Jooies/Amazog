<?php
spAddViewFunction('getavatar', 'togetavatar');
spAddViewFunction('onmore','content');
class main extends spController{
    public function index(){
        require("common.php");
        $main_count = $setupObj->find(array("skey"=>'main_count'));
        // Post---------------------------------
        if( $cid = $this->spArgs("cid") ){
            $cid = array('cid'=>$cid);
            $sum = $postObj->$cid;
            if( $sum > 0 ){
                $this->cid = $cid;
                $this->post = $postObj->spPager($this->spArgs('page', 1),$main_count['name'])->findAll($cid,'time desc');
                $this->pager = $postObj->spPager()->getPager();
                $pager = $postObj->spPager()->getPager();
            }else{
                $this->postno = "该分类下没有文章...";
            }
        }else{
            if( $s = $this->spArgs("s") ){
                $this->s = $s;
                $conditions = "title like '%".$s."%'";
                $sum = $postObj->findCount($conditions);
                if( $sum > 0 ){
                    $this->post = $postObj->spPager($this->spArgs('page', 1), $main_count['name'])->findAll($conditions);
                    $this->pager = $postObj->spPager()->getPager();
                    $pager = $postObj->spPager()->getPager();
                }else{
                    $this->postno = "没有与该关键词相关的内容...";
                }
            }
            else {
            $sum = $postObj->findCount();
            if( $sum > 0 ){
            $this->post = $postObj->spPager($this->spArgs('page', 1), $main_count['name'])->findAll(NULL,'time desc');
            $this->pager = $postObj->spPager()->getPager();
            $pager = $postObj->spPager()->getPager();
            }else{
                $this->postno = "没有文章...";
            }
            }
        }
        if ( $tag = $this->spArgs("tag") ){
            $this->tag = $tag;
            $sum = $postObj->findCount();
            if( $sum > 0){
            $this->post = $postObj->spPager($this->spArgs('page', 1), $main_count['name'])->findAll("FIND_IN_SET('{$tag}',tags)",'time desc');
            $this->pager = $postObj->spPager()->getPager();
            $pager = $postObj->spPager()->getPager();
        }else{
            $this->postno = "没有文章...";
        }
        }
        //简化Page
        if ($cid){
            $this->page_prev = spUrl("main","index",array("cid"=>$cid['cid'], "page"=>$pager['prev_page']));
            $this->page_next = spUrl("main","index",array("cid"=>$cid['cid'], "page"=>$pager['next_page']));
        }else{
        if ($s){
            $this->page_prev = spUrl("main","index",array("s"=>$s, "page"=>$pager['prev_page']));
            $this->page_next = spUrl("main","index",array("s"=>$s, "page"=>$pager['next_page']));
        }else{
        if ($tag) {
            $this->page_prev = spUrl("main","index",array("tag"=>$tag, "page"=>$pager['prev_page']));
            $this->page_next = spUrl("main","index",array("tag"=>$tag, "page"=>$pager['next_page']));
        }else{
            $this->page_prev = spUrl("main","index",array("page"=>$pager['prev_page']));
            $this->page_next = spUrl("main","index",array("page"=>$pager['next_page']));}
        }
        }
        // Show---------------------------------
        $this->display("skin/".$tpl."/index.html");//设置模板地址
    }
    
    public function post(){
        // Post---------------------------------
        require("common.php");
        $post_comments = $setupObj->find(array("skey"=>'post_comments'));
        $post_time = $setupObj->find(array("skey"=>'post_time'));
        $pid = $this->spArgs("pid");//文章ID
        $conditions = ("kind = 'post' AND pid = {$pid}");
        $comments = $commentsObj->spPager($this->spArgs('page', 1), $post_comments['name'])->findAll($conditions,'m_id desc');
        $pager = $postObj->spPager()->getPager();
        $this->pager = $postObj->spPager()->getPager();
        $this->page_prev = spUrl("main","post",array("pid"=>$pid, "page"=>$pager['prev_page']));
        $this->page_next = spUrl("main","post",array("pid"=>$pid, "page"=>$pager['next_page']));
        $conditions = array("pid" => $pid);
        $post = $postObj->find($conditions);
        $cid = $post['cid'];
        $conditions = array("cid"=>$cid);
        $categoryname = $categoryObj->find($conditions);
        $categoryname = $categoryname['name'];//获取文章标签名称
        // Comments-----------------------------
        // Show---------------------------------
        // -----------------------------Comments
        $this->comments = $comments;
        // ---------------------------------Post
        $conditions = array("pid"=>$pid);
        $post = $postObj->find($conditions);
        $this->tag = explode(',',$post['tags']);
        $this->post = $post;
        $this->category = $categoryname;
        // ----------------------------------Nav
        $this->categorynav = $categoryObj->findAll();
        $this->pagenav = $pageObj->findAll();
        $this->display("skin/".$tpl."/detail.html");    
    }

    public function comments_post(){
        require("common.php");
        $time = date('Y-m-d H:i:s');
        $info = $_SERVER['HTTP_USER_AGENT'];
        $ip = $_SERVER["REMOTE_ADDR"];
        $pid = $this->spArgs("pid");
        $content = $this->spArgs("content");
        $farr = array( "/\\s+/","/<(\\/?)(scrīpt|i?frame|style|html|body|title|link|meta|\\?|\\%)([^>]*?)>/isU","/(<[^>]*)on[a-zA-Z]+\\s*=([^>]*>)/isU",); 
        $tarr = array( " ", "＜\\\\1\\\\2\\\\3＞", "\\\\1\\\\2", ); 
        $content = preg_replace( $farr,$tarr,$content);
        $newcomment = array('m_author' => $user,'kind' => 'post','pid' => $pid,'content' => $content,'time' => $time,'info' => $info,'ip' => $ip,'email' => $email,'website' => $website);
        $result = $commentsObj->spVerifier($newcomment);
        if ( false == $result ){
            if ( $_COOKIE['postTime'] == "Cant"){
            $this->error("对不起，管理员设置{$post_time['name']}秒内禁止发表回复！");
            }else{
            $commentsObj->create($newcomment);
            $postObj->incrField(array("pid"=>$pid), 'm_count', 1);
            setcookie("postTime", "Cant", time()+$post_time['name']);
            $this->success("评论成功!",spUrl('main', 'post', array("pid"=>$pid, "cid"=>$cid)));
            }
        }else{
            foreach($result as $item){
            foreach($item as $msg){
            $this->error($msg);
            }
        }
        }
    }

    public function page(){
        // Post---------------------------------
        require("common.php");
        $page_comments = $setupObj->find(array("skey"=>'post_comments'));
        $pid = $this->spArgs("pid");
        $conditions = ("kind = 'page' AND pid = {$pid}");
        $comments = $commentsObj->spPager($this->spArgs('page', 1), $page_comments['name'])->findAll($conditions,'m_id desc');
        $pager = $pageObj->spPager()->getPager();//评论分页
        $this->page_prev = spUrl("main","page",array("pid"=>$pid, "page"=>$pager['prev_page']));
        $this->page_next = spUrl("main","page",array("pid"=>$pid, "page"=>$pager['next_page']));
        $this->pager = $pageObj->spPager()->getPager();
        $conditions = array("pid"=>$pid);
        $page = $pageObj->find($conditions);
        // Comments-----------------------------
        // -----------------------------------Me
        $time = date('Y-m-d H:i:s');
        $info = $_SERVER['HTTP_USER_AGENT'];
        $ip = $_SERVER["REMOTE_ADDR"];
        // Show---------------------------------
        $this->comments = $comments;
        $this->page = $page;
        $this->display("skin/".$tpl."/page.html");
    }

    public function comments_page(){
        require("common.php");
        $time = date('Y-m-d H:i:s');
        $info = $_SERVER['HTTP_USER_AGENT'];
        $ip = $_SERVER["REMOTE_ADDR"];
        $pid = $this->spArgs("pid");
        $content = $this->spArgs("content");
        $farr = array( "/\\s+/","/<(\\/?)(scrīpt|i?frame|style|html|body|title|button|link|meta|\\?|\\%)([^>]*?)>/isU","/(<[^>]*)on[a-zA-Z]+\\s*=([^>]*>)/isU",); 
        $tarr = array( " ", "＜\\\\1\\\\2\\\\3＞", "\\\\1\\\\2", ); 
        $content = preg_replace( $farr,$tarr,$content);
        $newcomment = array('m_author' => $user,'kind' => 'page','pid' => $pid,'content' => $content,'time' => $time,'info' => $info,'ip' => $ip,'email' => $email,'website' => $website);
        $result = $commentsObj->spVerifier($newcomment);
        if ( false == $result ){
            $commentsObj->create($newcomment);
            $pageObj->incrField(array("pid"=>$pid), 'm_count', 1);
            $this->success("评论成功!",spUrl('main', 'page', array("pid"=>$pid)));
        }else{
            foreach($result as $item){
            foreach($item as $msg){
            $this->error($msg);
            }
        }
    }
    }

    public function login(){
        // Date---------------------------------
        $userObj = spClass("m_admin");
        // Post---------------------------------
        $setupObj = spClass("m_setup");//调用设置数据库
        $skin = $setupObj->find(array("skey"=>'skin'));
        $tpl = $this->spArgs("tpl", $skin['name']);
        if($this->spArgs("username")){
            $username = $this->spArgs("username");
            $password = $this->spArgs("password");
            $row = array("username"=>$username,"password"=>$password);
            $userObj = spClass("m_admin");
            if(false == $userObj->spVerifier($this->spArgs())){                      
                if(false == $userObj->userlogin($username,$password)){
                    $this->tips = "登陆失败，账号或密码错误！";
                }else{
                    spClass("spAcl")->set('GBUSER');//给予用户权限
                    $value = $username;
                    setcookie("userName", $value, time()+360000);
                    $this->success("登陆成功!",spUrl("main","index"));
                }
            }else{
                foreach($result as $item){
                    foreach($item as $msg){
                    $this->tips = "登陆失败！";
                    }
                }
            }
        }
        // Show---------------------------------
        $this->display("skin/".$tpl."/main/login.html");
    }

    public function register(){
        // Date---------------------------------
        $userObj = spClass("m_reg");
        // Post---------------------------------
        $setupObj = spClass("m_setup");//调用设置数据库
        $skin = $setupObj->find(array("skey"=>'skin'));
        $tpl = $this->spArgs("tpl", $skin['name']);
        if($this->spArgs("username")){
            $username = $this->spArgs("username");
            $password = $this->spArgs("password");
            $password2 = $this->spArgs("password2");
            $email = $this->spArgs("email");
            $website = $this->spArgs("website");
            $row = array("username"=>$username,"password"=>md5($password),"email"=>$email,"website"=>$website);
            $result = $userObj->spVerifier($this->spArgs());
            if(false == $result){//验证
                if(true == $userObj->check_repeat($username)){
                        $userObj->create($row);
                        spClass("spAcl")->set('GBUSER');//给予用户权限
                        $value = $username;
                        setcookie("userName", $value, time()+3600);
                        $this->success("注册成功!点击确认返回首页（自动登陆）！",spUrl("main","index"));
                    }else{
                        $this->error("用户名已经被注册了！");
                    }
            }else{
                foreach($result as $item){
                    foreach($item as $msg){
                        $this->error($msg);
                    }
                }
           }
        }
        // Show---------------------------------
        $this->display("skin/".$tpl."/main/register.html");
    }

    public function logout(){
        // Post---------------------------------
        setcookie("userName", '');
        $this->success("注销成功!",spUrl("main","index"));
    }

    public function archiver(){
        require("common.php");
        // Post-------------------------------
        $prefix = $GLOBALS['G_SP']['db']['prefix'];
        $sql = "select *,FROM_UNIXTIME(time,'%e') as day,FROM_UNIXTIME(time,'%Y') as year,FROM_UNIXTIME(time,'%c') as month from {$prefix}post order by time desc";
        $result = $postObj->findSql($sql);
        $this->newmonth = 0;
        $this->archiver = $result;
        $this->count = $postObj->findCount($result['pid']);
        // Me---------------------------------
        $user = $_COOKIE['userName'];
        $email = array("username"=>$user);
        $email = $userObj->find($email);
        $email = $email['email'];
        // Show---------------------------------
        $this->display("skin/".$tpl."/archiver.html");
    }
    
}