<?php
/**
 *    #Case		bwvip
 *    #Page		field_orderinfoModel.class.php (订场内容)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class field_orderinfoModel extends Model{
    //添加订场信息
    public function add_orderinfo($data)
    {
        if(empty($data))
        {
            return false;   
        }
        
        $insert_id=M('field_orderinfo')->add($data);
        if($insert_id != false) {
            return $insert_id;
        }
        
        return false;
    }
    
    //修改订场信息
    public function edit_orderinfo($field_orderinfo_id, $data)
    {
        if(empty($field_orderinfo_id) || empty($data)) 
        {
            return false;
        }
        
        $res = M('field_orderinfo')->where("field_orderinfo_id='{$field_orderinfo_id}'")->save($data);
       
        if($res != false) 
        {
            return true;
        }
       
        return false;
    }
    
    //删除订场信息记录
    public function del_orderinfo($field_orderinfo_id)
    {
        if(empty($field_orderinfo_id)) {
            return false;
        }
        $res = M('field_orderinfo')->where("field_orderinfo_id='{$field_orderinfo_id}'")->delete();
        
        if($res != false) 
        {
            return true;    
        }
        
        return false;
    }
    public function get_orderinfo_byid($orderinfo_id)
    {
        if(empty($orderinfo_id)) {
            return false;
        }
        
        $where = " field_orderinfo_id='{$orderinfo_id}'";
        
        $info = M('field_orderinfo')->where($where)->find();
        
        if($info!=false) {
            return $info;
        }
        
        return false;
    }
    //list page 获取订场内容列表
    public function get_orderinfo_list($field_uid, $page=1, $page_size=10) 
    {
        if(!empty($field_uid)) {
             $where = " field_uid='{$field_uid}'";
        }
        $page = max(1,$page);
       
        $sort = " field_orderinfo_id desc";
        $offset = ($page-1)*$page_size;
        $data_list['item']=M('field_orderinfo')->where($where)->order($sort)->select();
        
		if($data_list != false) 
        {
            //分页
            $data_list["total"] = M('field_orderinfo')->where($where)->count();
            import ("@.ORG.Page");
    		$page = new page ($data_list["total"], $page_size );
    		$data_list["pages"] = $page->show();
            return $data_list;    
        }
        return false;
    }
    

}
?>