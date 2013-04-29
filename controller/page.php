<?php
class page extends spController{
    function index(){
    }
    
    function add(){
        import("markdown.php");
        //add post function
        if($title = $this->spArgs("title")){
            $pageObj = spClass("m_page");
            /*
                post table : title,content,time,cid
            */
            $content = $this->spArgs("content");
            $time = date('Y-m-d H:i:s');
            $row = array("title"=>$title,"content"=>Markdown($content),"time"=>$time);           
            $result = $pageObj->spVerifier($row);
            if(false == $result){//access the verify
                    $pageObj->create($row);
                    /*
                        tag table : name pid
                    */
                    //write the tag and pid into the table tag 
                    $pid = $pageObj->find(array("title"=>$title));
                    $pid = $pid['pid'];
                    $row = array("pid"=>$pid);
                    
                    $this->success("添加成功!",spUrl("page","add"));
            }else{
                foreach($result as $item){
                    foreach($item as $msg){
                        $this->error($msg,spUrl("page","add"));
                    }
                }
           }
            
        }
        $this->category = spClass("m_category")->findall();
        $this->display("page/add.html");
    }
    
    function admin(){
        //post admin page
        $pageObj = spClass("m_page");
        $this->page = $pageObj->spPager($this->spArgs('page', 1), 10)->findAll(null,'time desc');
        $this->pager = $pageObj->spPager()->getPager();
        $this->display("page/admin.html");
    }
    
    function modify(){
        import("markdownify.php");
        import("markdown.php");
        //modify post function
        $pid = $this->spArgs("pid");
        $pageObj = spClass("m_page");
        $conditions = array("pid"=>$pid);
        $page = $pageObj->find($conditions);
        $content = new Markdownify;
        $this->page = $page;
        $this->content = $content->parseString(Markdown($page['content']));
        if($title = $this->spArgs("title")){
            $content = $this->spArgs("content");
            $time = date('Y-m-d H:i:s');
            $row = array("title"=>$title,"content"=>Markdown($content),"time"=>$time);
            $result = $pageObj->spVerifier($row);
            if(false == $result){//access the verify
                $pageObj->update($conditions,$row);
                $this->success("修改成功!",spUrl("page","admin"));
            }else{
                foreach($result as $item){
                    foreach($item as $msg){                       
                        $this->error($msg,spUrl("page","modify",array("pid"=>$pid)));
                    }
                }
            }
        }  
        $this->display("page/modify.html");
    }
    
    function delete(){
        //delete post function
        $pageObj = spClass("m_page");
        $pid = $this->spArgs("pid");
        $conditions = array("pid"=>$pid);
        if($pageObj->delete($conditions)){
            $this->success("Delete Successfully!",spUrl("page","admin"));
        }else{
            $this->error("Delete Failed!",spUrl("page","admin"));
        }
    }
}