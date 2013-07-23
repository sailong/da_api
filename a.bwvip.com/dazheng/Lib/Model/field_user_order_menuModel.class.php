<?php
/**
 *    #Case		bwvip
 *    #Page		field_menuModel.class.php (订餐信息)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class field_user_order_menuModel extends Model{
    private $_table_user_menu = 'field_user_menu';
    private $_table_user_menu_rel = 'field_user_menu_rel';
    //会员订餐记录列表
    public function get_user_order_list($realname,$uid,$field_uid,$page=1,$page_size=20) {
        if(empty($field_uid)) {
            return false;
        }
        $where = " a.field_uid='{$field_uid}' and a.uid=b.uid";
        if(!empty($uid)) {
            $where .= " and a.uid='{$uid}'";
        }
        if(!empty($realname)) {
            $where .= " and b.realname like '%{$realname}%'";
        }
        
		if(get("starttime")!="")
		{
		    $starttime = strtotime(get("starttime"));
			$where .=" and a.filed_user_menu_addtime>=".$starttime." ";
		}
		if(get("endtime")!="")
		{
		    $endtime = strtotime(get("endtime"));
            $endtime = intval($endtime)+86400;
			$where .=" and a.filed_user_menu_addtime<".$endtime." ";
		}
        $sort = " a.rel_id desc";
        $data["item"]=M("{$this->_table_user_menu_rel} a,pre_common_member_profile b")->field('a.rel_id,a.uid,a.field_uid,a.field_1stmenu_type,a.field_1stmenu_type_nums,a.field_user_menu_phone,a.field_user_menu_is_finished,a.filed_user_menu_addtime,b.realname')->where("$where")->order($sort)->page($page.",".$page_size)->select();
        //echo M()->getLastSql();
        foreach($data["item"] as $key=>$val) {
            $user_uids[$val['uid']]=$val['uid'];
        }
        if(empty($data["item"])) 
        {
            return false;    
        }
		
		$data["total"] = M("{$this->_table_user_menu_rel} a,pre_common_member_profile b")->field('a.rel_id,a.uid,a.field_uid,a.field_1stmenu_type,a.field_1stmenu_type_nums,a.field_user_menu_phone,a.field_user_menu_is_finished,a.filed_user_menu_addtime,b.realname')->where("$where")->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
        
    }
}
?>