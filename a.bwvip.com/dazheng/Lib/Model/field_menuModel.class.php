<?php
/**
 *    #Case		bwvip
 *    #Page		field_menuModel.class.php (订餐信息)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class field_menuModel extends Model{
    private $_table_1stmenu = 'field_1stmenu';
    private $_table_2ndmenu = 'field_2ndmenu';
    //添加订场信息
    public function add_1stmenu($data)
    {
        if(empty($data))
        {
            return false;   
        }
        
        $insert_id=M($this->_table_1stmenu)->add($data);
        if($insert_id != false) {
            return $insert_id;
        }
        
        return false;
    }
    
    //修改订场信息
    public function edit_1stmenu($field_1stmenu_id, $data)
    {
        if(empty($field_1stmenu_id) || empty($data)) 
        {
            return false;
        }
        
        $res = M($this->_table_1stmenu)->where("field_1stmenu_id='{$field_1stmenu_id}'")->save($data);
       
        if($res != false) 
        {
            return true;
        }
       
        return false;
    }
    
    //删除订场信息记录
    public function del_1stmenu($field_1stmenu_id)
    {
        if(empty($field_1stmenu_id)) {
            return false;
        }
        $res = M($this->_tablename)->where("field_1stmenu_id='{$field_1stmenu_id}'")->delete();
        
        if($res != false) 
        {
            return true;    
        }
        
        return false;
    }
    
    //list page 获取一级列表
    public function get_1stmenu_list($field_uid, $page=1, $page_size=20) 
    {
        if(empty($field_uid)) {
            return false;
        }
        $where = " field_uid='{$field_uid}'";
        $sort = " field_1stmenu_id desc";
        $data["item"]=M($this->_table_1stmenu)->where($where)->order($sort)->page($page.",".$page_size)->select();
        if(empty($data["item"])) 
        {
            return false;    
        }
		
		$data["total"] = M($this->_table_1stmenu)->where($where)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
    }
    
    //获取一级菜单信息
    public function get_1stmenu_info($field_1stmenu_id)
    {
        if(empty($field_1stmenu_id)) {
            return false;
        }
        
        $where = "field_1stmenu_id='{$field_1stmenu_id}'";
        $info = M($this->_table_1stmenu)->where($where)->find();
        if(empty($info)) {
            return false;
        }
        if($info['field_1stmenu_type'] == 1) 
        {
            $info['field_menu_type_name'] = '餐厅用餐 ';
        }elseif($info['field_1stmenu_type'] == 2){
            $info['field_menu_type_name'] = '场下送餐 ';
        }
        return $info;
    }
    
    //list page 获取二级内容列表
    public function get_2ndmenu_list($field_1stmenu_id, $page=1, $page_size=20) 
    {
        if(empty($field_1stmenu_id)) {
            return false;
        }
        $where = " field_1stmenu_id='{$field_1stmenu_id}'";
        $sort = " field_2ndmenu_id desc";
        $data["item"]=M($this->_table_2ndmenu)->where($where)->order($sort)->page($page.",".$page_size)->select();
        if(empty($data["item"])) 
        {
            return false;    
        }
		
		$data["total"] = M($this->_table_2ndmenu)->where($where)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
    }
    
    //获取一级菜单信息
    public function get_2ndmenu_info($field_2ndmenu_id)
    {
        if(empty($field_2ndmenu_id)) {
            return false;
        }
        
        $where = "field_2ndmenu_id='{$field_2ndmenu_id}'";
        $info = M($this->_table_2ndmenu)->where($where)->find();
        if(empty($info)) {
            return false;
        }
        if($info['field_1stmenu_type'] == 1) 
        {
            $info['field_menu_type_name'] = '餐厅用餐 ';
        }elseif($info['field_1stmenu_type'] == 2){
            $info['field_menu_type_name'] = '场下送餐 ';
        }
        return $info;
    }
    
	
	//list and page
	function qiutong_list_pro($bigwhere="", $page_size=20, $sort=" qiutong_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("starttime")!="")
		{
			$where .=" and qiutong_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and qiutong_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("qiutong")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("qiutong")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function qiutong_select_pro($bigwhere="",$limit=999999, $sort=" qiutong_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("qiutong")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("qiutong")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>