<?php
/**
 *    #Case		bwvip
 *    #Page		User_ticket_logAction.class.php (验票)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class user_ticket_logAction extends AdminAuthAction
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function user_ticket_log()
	{
		
		
		$event_select=D('event')->event_select_pro(" ");
		$this->assign('event_select',$event_select['item']);
		//echo '<pre>';
		//var_dump($event_select['item']);die;
		$ticket_id = get("ticket_id");
		$ticket_id_sql = '';
		if($ticket_id){
			$ticket_id_sql = " and ticket_id='{$ticket_id}'";
		}
		
		$user_ticket_log_status = get('user_ticket_log_status');
		$user_ticket_log_status_sql = '';
		if($user_ticket_log_status){
			$user_ticket_log_status_sql = " and user_ticket_log_status='{$user_ticket_log_status}'";
		}
		
		$list=D("user_ticket_log")->user_ticket_log_list_pro($ticket_id_sql.$user_ticket_log_status_sql);
		
		foreach($list["item"] as $key=>$val)
		{
			$ticket_ids[$val['ticket_id']] = $val['ticket_id'];
		}
		
		if($ticket_ids){
			$ticket_list = M('ticket')->field('ticket_name,ticket_id,event_id')->where("ticket_id in('".implode("','",(array)$ticket_ids)."')")->select();
			
		}
		$event_ids = array();
		if($ticket_list)
		{
			foreach($ticket_list as $key=>$val){
				unset($ticket_list[$key]);
				$ticket_list[$val['ticket_id']] = $val;
				$event_ids[$val['event_id']] = $val['event_id'];
			}
			foreach($list['item'] as $key=>$val){
				$list['item'][$key]['event_id'] = $ticket_list[$val['ticket_id']]['event_id'];
			}
			$event_list_tmp = array();
			if($event_ids){
				$event_list_tmp = M('event')->field('event_id,event_name')->where("event_id in('".implode("','",$event_ids)."')")->select();
			}
			$event_list = array();
			foreach($event_list_tmp as $key=>$val)
			{
				unset($event_list_tmp[$key]);
				$event_list[$val['event_id']] = $val;
			}
		}
		
		$this->assign('event_list',$event_list);
		$this->assign("ticket_list",$ticket_list);
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","验票");
    	$this->display();
	}

	public function user_ticket_log_add()
	{
		
		$this->assign("page_title","添加验票");
    	$this->display();
	}

	public function user_ticket_log_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["uid"]=post("uid");
			$data["ticket_id"]=post("ticket_id");
			$data["user_ticket_code"]=post("user_ticket_code");
			$data["user_ticket_log_source"]=post("user_ticket_log_source");
			$data["user_ticket_log_status"]=post("user_ticket_log_status");
			$data["user_ticket_log_addtime"]=time();
			
			$list=M("user_ticket_log")->add($data);
			$this->success("添加成功",U('admin/user_ticket_log/user_ticket_log'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/user_ticket_log/user_ticket_log_add'));
		}

	}


	public function user_ticket_log_edit()
	{
		if(intval(get("user_ticket_log_id"))>0)
		{
			$data=M("user_ticket_log")->where("user_ticket_log_id=".intval(get("user_ticket_log_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改验票");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function user_ticket_log_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["user_ticket_log_id"]=post("user_ticket_log_id");
			$data["uid"]=post("uid");
			$data["ticket_id"]=post("ticket_id");
			$data["user_ticket_code"]=post("user_ticket_code");
			$data["user_ticket_log_source"]=post("user_ticket_log_source");
			$data["user_ticket_log_status"]=post("user_ticket_log_status");
			
			$list=M("user_ticket_log")->save($data);
			$this->success("修改成功",U('admin/user_ticket_log/user_ticket_log'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/user_ticket_log/user_ticket_log'));
		}

	}

	public function user_ticket_log_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("user_ticket_log")->where("user_ticket_log_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function user_ticket_log_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_user_ticket_log set user_ticket_log_state=1 where user_ticket_log_id=".$ids_arr[$i]." ");
			}
			if($res)
			{
				echo "succeed^审核成功";
			}
			else
			{
				echo "error^审核失败";
			}			
			
		}
	}

	public function user_ticket_log_detail()
	{
		if(intval(get("user_ticket_log_id"))>0)
		{
			$data=M("user_ticket_log")->where("user_ticket_log_id=".intval(get("user_ticket_log_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["user_ticket_log_name"]."验票");
				$this->display();
			}
			else
			{
				$this->error("您该问的信息不存在");	
			}
			
		}
		else
		{
			$this->error("您该问的信息不存在");
		}

	}
	
	//根据赛事id(event_id)获取相关赛事门票列表
	public function get_event_ticket_list()
	{
		$event_id = get('event_id');
		
		if(empty($event_id)){
			$this->ajaxReturn(null,'参数错误',0);
		}
		$ticket_list = M('ticket')->where("event_id='{$event_id}'")->select();
		
		if($ticket_list){
			$this->ajaxReturn($ticket_list,'成功',1);
		}
		
		$this->ajaxReturn(null,'失败',0);
	}


	

}
?>