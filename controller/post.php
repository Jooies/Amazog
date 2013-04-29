<?php
class post extends spController{
    function index(){
    }
    
    function add(){
        import("markdown.php");
        if($title = $this->spArgs("title")){
            // Date---------------------------------
            $postObj = spClass("m_post");
            // Post---------------------------------
            $content = $this->spArgs("content");
            $time = time();
            $category = $this->spArgs("category");//需要CID
            $cid = spClass("m_category")->find(array("name"=>$category));
            $cid = $cid["cid"];
            $tags = $this->spArgs("tags");//Tag表:aaa,bbb,ccc (string)
            $author = $_COOKIE['userName'];
            $row = array("title"=>$title,"content"=>Markdown($content),"author"=>$author,"frist_time"=>$time,"time"=>$time,"cid"=>$cid,"tags"=>$tags);           
            $result = $postObj->spVerifier($row);
            if(false == $result){//验证数据
                    $postObj->create($row);
                    $pid = $postObj->find(array("title"=>$title,"content"=>$content));
                    $pid = $pid['pid'];
                    $tag = explode(',',substr($tags,0));
                    while (list($key,$value) = each($tag)) {
                        if($value != ""){
                        if(true == spClass("m_tag")->check_repeat($value)){
                            $row = array("name"=>$value,"count"=>1);
                            spClass("m_tag")->create($row);
                        }else{
                            spClass("m_tag")->incrField(array("name"=>$value), 'count', 1);
                        }
                    }
                    }
                    $this->success("添加成功!",spUrl("post","admin"));
            }else{
                foreach($result as $item){
                    foreach($item as $msg){
                        $this->error($msg,spUrl("post","add"));
                    }
                }
           }
            
        }
        // Show---------------------------------
        $this->category = spClass("m_category")->findall();
        $this->display("admin/post/add.html");
    }
    
    function admin(){
        // Date---------------------------------
        $postObj = spClass("m_post");
        // Post---------------------------------
        $this->post = $postObj->spPager($this->spArgs('page', 1), 10)->findAll(null,'time desc');
        $this->pager = $postObj->spPager()->getPager();
        // Show---------------------------------
        $this->display("admin/post/admin.html");
    }
    
    function modify(){
        import("markdownify.php");
        import("markdown.php");
        // 编辑文章
        $pid = $this->spArgs("pid");
        $postObj = spClass("m_post");
        $conditions = array("pid"=>$pid);
        $post = $postObj->find($conditions);
        $content = new Markdownify;
        $this->post = $post;
        $this->content = $content->parseString(Markdown($post['content']));
        $this->category = spClass("m_category")->findall();
        // 检查数据
        if($title = $this->spArgs("title")){
            $content = $this->spArgs("content");
            $time = time();
            $category = $this->spArgs("category");//need to change into cid
            $cid = spClass("m_category")->find(array("name"=>$category));
            $cid = $cid["cid"];
            $tags = $this->spArgs("tags");//Tag表:aaa,bbb,ccc (string)
            $row = array("title"=>$title,"content"=>Markdown($content),"time"=>$time,"cid"=>$cid,"tags"=>$tags);
            $result = $postObj->spVerifier($row);
            // 验证数据
            if(false == $result){//access the verify
                $postObj->update($conditions,$row);
                if(strpos($post['tags'],",")){
                    $tag = explode(',',substr($post['tags']),0);
                    while (list($key,$value) = each($tag)) {
                    spClass("m_tag")->decrField(array("name"=>$value), 'count');
                    if (spClass("m_tag")->find(array("name"=>$value,'count' => 0))){
                        spClass("m_tag")->delete(array("name"=>$value));
                    }
                    }
                }else{
                    $tag = $post['tags'];
                    spClass("m_tag")->decrField(array("name"=>$tag), 'count');
                    if (spClass("m_tag")->find(array("name"=>$tag,'count' => 0))){
                    spClass("m_tag")->delete(array("name"=>$tag));
                    }
                }
                $tag = explode(',',substr($tags,0)); // 增加标签
                while (list($key,$value) = each($tag)) {
                    if($value != ""){
                    if(true == spClass("m_tag")->check_repeat($value)){
                        $row = array("name"=>$value,"count"=>1);
                        spClass("m_tag")->create($row);
                        }else{
                        spClass("m_tag")->incrField(array("name"=>$value), 'count');
                        }
                    }
                }
                // 成功提示
                $this->success("修改成功!",spUrl("post","admin"));
            }else{// 错误提示
                foreach($result as $item){
                    foreach($item as $msg){                       
                        $this->error($msg,spUrl("post","modify",array("pid"=>$pid)));
                    }
                }
            }
        }  
        $this->display("admin/post/modify.html");
    }
    
    function delete(){
        // 删除文章函数
        $postObj = spClass("m_post");
        $pid = $this->spArgs("pid");
        $conditions = array("pid"=>$pid);
        $post = $postObj->find($conditions);
        $tag = explode(',',$post['tags']); // 修改标签文章数量
        while (list($key,$value) = each($tag)) {
            spClass("m_tag")->incrField(array("name"=>$value), 'count', -1);
            if (spClass("m_tag")->find(array("name"=>$value,'count' => 0))){
                spClass("m_tag")->delete(array("name"=>$value));
            }
        }
        if($postObj->delete($conditions)){
            $this->success("删除成功!",spUrl("post","admin"));
        }else{
            $this->error("删除失败!",spUrl("post","admin"));
        }
    }
}