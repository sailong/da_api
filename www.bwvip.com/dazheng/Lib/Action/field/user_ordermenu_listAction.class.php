<?php
/**
 *    #Case		mlh
 *    #Page		user_ordermenu_listAction(会员订餐记录)
 *
 *    @author		changsailong
 *    @E-mail		653690921@qq.com
 */
class user_ordermenu_listAction extends field_publicAction
{

    private $http_url = '';
	public function _basic()	
	{
		parent::_basic();
	}
	
	public function user_order_list() {
	    $page = get('p');
	    $page = max(1,$page);
        $language = get('language');
        if(empty($language)) {
            $language = 'cn';
        }
        $uid = get('k');
        $realname = get('realname');
	    $field_uid = 1186;
	    $field_user_order_menu_model = new field_user_order_menuModel();
	    $list = $field_user_order_menu_model->get_user_order_list($realname,$uid,$field_uid,$page,20);
	    foreach($list['item'] as $key=>&$val) {
	        if($val['field_1stmenu_type'] == 1){
	            $val['type_name'] = '餐厅用餐';
	            $val['field_1stmenu_type_nums']='用餐人数：'.$val['field_1stmenu_type_nums'];
	        }elseif($val['field_1stmenu_type'] == 2){
	            $val['type_name'] = '场下送餐';
	            $val['field_1stmenu_type_nums']='球童ID：'.$val['field_1stmenu_type_nums'];
	        }
	        if($val['field_user_menu_is_finished'] == 'N') {
	            $val['finished_text'] = "还未用餐";
	        }else{
	            $val['finished_text'] = "已用完餐";
	        }
	        $val['field_user_menu_phone'] = substr($val['field_user_menu_phone'],-11);
	    }
	    $this->assign('list',$list['item']);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
	    
	    $this->display('user_ordermenu_list');
	    
	}
	//订餐详情
	public function order_menu_detail() {
	    $rel_id = get('rel_id');
	    if(empty($rel_id)) {
	        $this->error("操作失败",U('field/user_ordermenu_list/user_order_list',array()));exit;
	    }
	    $info = M('field_user_menu a,tbl_field_2ndmenu b')->where("a.rel_id='{$rel_id}' and a.field_2ndmenu_id=b.field_2ndmenu_id")->select();
	    $this->assign('list',$info);
	    $this->display('user_ordermenu_detail');
	}
	//修改订餐记录
	public function upd_order_menu() {
	    $rel_id = get('rel_id');
	    if(empty($rel_id)) {
	        $this->error("操作失败",U('field/user_ordermenu_list/user_order_list',array()));exit;
	    }
	    $info = M('field_user_menu_rel')->where("rel_id='{$rel_id}'")->find();
	    if(empty($info)){
	        $this->error("信息错误",U('field/user_ordermenu_list/user_order_list',array()));exit;
	    }
	    
	    $this->assign('data',$info);
	    $this->display('upd_order_menu');
	}
	public function upd_order_menu_action(){
	    if(M()->autoCheckToken($_POST))
		{
		    $rel_id = post('rel_id');
		    $data['uid'] = post('uid');
	        $data['field_uid'] = post('field_uid');
		    $data['field_1stmenu_type_nums'] = post('field_1stmenu_type_nums');
		    $data['field_user_menu_phone'] = post('field_user_menu_phone');
		    $data['field_user_menu_is_finished'] = post('field_user_menu_is_finished');
		    $data['filed_user_menu_addtime'] = time();
		    $list=M("field_user_menu_rel")->where("rel_id='{$rel_id}'")->save($data);
		    
		    if($list!=false)
			{
				$this->success("修改成功",U('field/user_ordermenu_list/user_order_list',array()));exit;
			}
			else
			{				
				$this->error("修改失败",U('field/user_ordermenu_list/user_order_list',array()));exit;
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/user_ordermenu_list/user_order_list',array()));exit;
		}
	}
	
	//删除订餐记录
	public function del_order_menu(){
	    $ids = post('ids');
	    if(empty($ids)) {
	        echo "error^操作失败";
	        exit;
	    }
	    $where = "rel_id='$ids'";
	    //删除订餐详情
	    M('field_user_menu')->where($where)->delete();
	    //删除订餐记录
       $res = M('field_user_menu_rel')->where($where)->delete();
	    if($res != false) {
	        echo "succeed^操作成功";exit;
	    }
	    echo "error^操作失败";exit;
	}
	//修改用户订餐菜单中的某项菜
    public function upd_user_order_menu() {
	    $id = get('id');
	    $rel_id = get('rel_id');
	    if(empty($id)) {
	        $this->error("操作失败",U('field/user_ordermenu_list/order_menu_detail',array('rel_id'=>$rel_id)));exit;
	    }
	    $info = M('field_user_menu a,tbl_field_2ndmenu b')->where("a.id='{$id}' and a.field_2ndmenu_id=b.field_2ndmenu_id")->find();
	    
	    $this->assign('info',$info);
        $this->display('upd_user_order_menu');
	}
    public function upd_user_order_menu_action() {
        $id = post('id');
	    $rel_id = post('rel_id');
	    if(empty($id)) {
        $this->error("操作失败",U('field/user_ordermenu_list/order_menu_detail',array('rel_id'=>$rel_id)));exit;
	    }
	    $data['field_menu_nums'] = post('field_menu_nums');
	    $data['field_user_menu_is_finished'] = post('field_user_menu_is_finished');
	    //修改用户菜单详情
	    $res = M('field_user_menu')->where("id='{$id}'")->save($data);
	    if($res != false) {
	        $this->success("修改成功",U('field/user_ordermenu_list/order_menu_detail',array('rel_id'=>$rel_id)));exit;
	    }
	    $this->error("操作失败",U('field/user_ordermenu_list/order_menu_detail',array('rel_id'=>$rel_id)));exit;
	    
	}
	
	//删除用户订餐菜单中的某项菜
	public function del_user_order_menu() {
	    $ids = post('ids');
	    if(empty($ids)) {
	        echo "error^操作失败";
	        exit;
	    }
	    $where = "id='$ids'";
	    //删除订餐详情
	    $res = M('field_user_menu')->where($where)->delete();
	    if($res != false) {
	        echo "succeed^操作成功";exit;
	    }
	    echo "error^操作失败";exit;
	    
	}
}
?>