<?php
class category extends spController{
    function index(){

    }
    
    function add(){
        $setupObj = spClass("m_setup");
        // 添加标签
        $categoryObj = spClass("m_category");
        if($name = $this->spArgs("name")){
            $row = array("name"=>$name,"skin"=>$this->spArgs("skin"),"description"=>$this->spArgs("d"));
            $result = $categoryObj->spVerifier($row);
            if(false == $result){//access the verify
               if(true == $categoryObj->check_repeat($name)){//no repeat name
                    $categoryObj->create($row);
                    $this->success("添加成功!",spUrl("category","admin"));
                }else{
                    $this->error("添加失败,分类已经存在!",spUrl("category","add"));
                }
            }else{
                foreach($result as $item){
                    foreach($item as $msg){
                        $this->error($msg,spUrl("category","add"));
                    }
                }
            }
        }
        $skin = $setupObj->find(array("skey"=>'skin'));
        $this->skin = $setupObj->getDir('template/skin');
        $this->category = $categoryObj->findall();
        $this->display("category/add.html");
    }
    
    function admin(){
        $categoryObj = spClass("m_category");
        $this->category = $categoryObj->spPager($this->spArgs('page', 1), 10)->findAll();
        $this->pager = $categoryObj->spPager()->getPager();
        $this->display("category/admin.html");
    }
    
    function modify(){
        //modify category function
        $cid = $this->spArgs("cid");
        $categoryObj = spClass("m_category");
        $conditions = array("cid"=>$cid);
        $category = $categoryObj->find($conditions);
        $this->category = $category;
        if($skin = $this->spArgs("skin")){
            $row = array("name"=>$this->spArgs("name"),"skin"=>$skin,"description"=>$this->spArgs("d"));
            $result = $categoryObj->spVerifier($row);
            if(false == $result){//access the verify
               if(true == $categoryObj->check_repeat($name,$category['name'])){//no repeat name
                    $categoryObj->update($conditions,$row);
                    $this->success("修改成功！",spUrl("category","admin"));
                }else{                   
                    $this->error("修改失败！原因：名称重复！");
                }
            }else{
                foreach($result as $item){
                    foreach($item as $msg){                       
                        $this->error($msg,spUrl("category","modify",array("cid"=>$cid)));
                    }
                }
            }
        }  
        $this->display("category/modify.html");
        
    }
    
    function delete(){
        //delete category function
        $categoryObj = spClass("m_category");
        $postObj = spClass("m_post");
        $cid = $this->spArgs("cid");
        $con = array("cid"=>$cid);
        $sum = $postObj->findCount($con);
        if($sum >= 1){
            $this->error("删除失败！分类内还有文章！",spUrl("category","admin"));
        }{
        if($categoryObj->delete($con)){
            $this->success("删除成功!",spUrl("category","admin"));
        }else{
            $this->error("删除失败!",spUrl("category","admin"));
        }
        }
    }
}