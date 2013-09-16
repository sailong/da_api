<?php
/**
 *    #Case		bwvip
 *    #Page		User_ticketAction.class.php (门票领取)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class user_ticketAction extends field_publicAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function user_ticket()
	{
	
		$ticket_id = get('k');
		$event_id = get('event_id');
		$field_uid = $_SESSION['field_uid'];
		
		$event_ids = array();
		$event_ids_sql = '';
		if($event_ids){
			$event_ids_sql = " or event_id in('".implode("','",(array)$event_ids)."')";
		}
		$event_list = M('event')->where("field_uid='{$field_uid}' {$event_ids_sql}")->select();
		foreach($event_list as $key=>$val){
			unset($event_list[$key]);
			$event_list[$val['event_id']] = $val;
			$event_ids[$val['event_id']]=$val['event_id'];
		}
		if($event_id){
			$event_ids = $event_id;
		}
		//$event_ids_sql = '';
		if($event_ids){
			//$event_ids_sql = " and event_id in('".implode("','",$event_ids)."')";
			$ticket_list = M('ticket')->where("event_id in('".implode("','",(array)$event_ids)."')")->select();
			//echo M()->getLastSql();
			foreach($ticket_list as $key=>$val){
				unset($ticket_list[$key]);
				$ticket_list[$val['ticket_id']] = $val;
				$ticket_ids[$val['ticket_id']] = $val['ticket_id'];
			}
			$ticket_ids_sql = '';
			if($ticket_ids){
				$ticket_ids_sql = " and ticket_id in('".implode("','",(array)$ticket_ids)."')";
			}
		}
		$ticket_id_sql = '';
		if($ticket_id){
			$ticket_id_sql = " and ticket_id='{$ticket_id}'";
		}else{
			$ticket_id_sql = $ticket_ids_sql;
		}
		//echo $ticket_ids_sql;
		if($ticket_id_sql){
			$list=D("user_ticket")->user_ticket_list_pro($ticket_id_sql);
		}
		
		
		$this->assign("event_list",$event_list);
		$this->assign("ticket_list",$ticket_list);
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","门票领取");
    	$this->display();
	}

	public function user_ticket_add()
	{
		
		$this->assign("page_title","添加门票领取");
    	$this->display();
	}
	//获取随机字符串
	public function get_randmod_str(){
		$str = 'abcdABCefgD69EFhigkGHI7nm8JKpqMNrs3PQRtuS5vw4TxyU1VWzXYZ20';
		$len = strlen($str); //得到字串的长度;

		//获得随即生成的积分卡号
		$s = rand(0, 1);
		$serial = '';

		for($s=1;$s<=10;$s++)
		{
		   $key     = rand(0, $len-1);//获取随机数
		   $serial .= $str[$key];
		}

	   //strtoupper是把字符串全部变为大写
	   $serial = strtoupper(substr(md5($serial.time()),10,10));
	   if($s)
	   {
		  $serial = strtoupper(substr(md5($serial),mt_rand(0,22),10));
	   }
	   
	   return $serial;
	}

	public function user_ticket_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			
			$data["uid"]=post("uid");
			$ticket_id = $data["ticket_id"]=post("ticket_id");
			
			$ticket_info = M('ticket')->where("ticket_id='{$ticket_id}'")->find();
			
			$data["event_id"]=$ticket_info['event_id'];
			$data["ticket_starttime"]=$ticket_info['ticket_starttime'];
			$data["ticket_endtime"]=$ticket_info['ticket_endtime'];
			$data["ticket_times"]=$ticket_info['ticket_times'];
			$data["user_ticket_code"]=$this->get_randmod_str();
			$data["ticket_type"]=post('ticket_type');
			if($_FILES["user_ticket_codepic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/user_ticket/");
				$data["user_ticket_codepic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["user_ticket_nums"]=post("user_ticket_nums");
			$data["user_ticket_sex"]=post("user_ticket_sex");
			$data["user_ticket_age"]=post("user_ticket_age");
			$data["user_ticket_address"]=post("user_ticket_address");
			$data["user_ticket_imei"]=post("user_ticket_imei");
			$data["user_ticket_company"]=post("user_ticket_company");
			$data["user_ticket_company_post"]=post("user_ticket_company_post");
			$data["user_ticket_realname"]=post("user_ticket_realname");
			//$data["user_ticket_cardtype"]=post("user_ticket_cardtype");
			//user_ticket_nums,user_ticket_sex,user_ticket_age,user_ticket_address,user_ticket_mobile,user_ticket_imei,user_ticket_company,user_ticket_company_post,user_ticket_status
			//$data["user_ticket_card"]=post("user_ticket_card");
			$data["user_ticket_mobile"]=post("user_ticket_mobile");
			$data["user_ticket_status"]=post("user_ticket_status");
			$data["user_ticket_addtime"]=time();
			
			$list=M("user_ticket")->add($data);
			$this->success("添加成功",U('field/user_ticket/user_ticket'));
		}
		else
		{
			$this->error("不能重复提交",U('field/user_ticket/user_ticket_add'));
		}

	}


	public function user_ticket_edit()
	{
		if(intval(get("user_ticket_id"))>0)
		{
			$data=M("user_ticket")->where("user_ticket_id=".intval(get("user_ticket_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改门票领取");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function user_ticket_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["user_ticket_id"]=post("user_ticket_id");
			$data["uid"]=post("uid");
			$ticket_id = $data["ticket_id"]=post("ticket_id");
			$ticket_info = M('ticket')->where("ticket_id='{$ticket_id}'")->find();
			$data["event_id"]=$ticket_info['event_id'];
			$data["ticket_starttime"]=$ticket_info['ticket_starttime'];
			$data["ticket_endtime"]=$ticket_info['ticket_endtime'];
			$data["ticket_times"]=$ticket_info['ticket_times'];
			//$data["user_ticket_code"]=post("user_ticket_code");
			if($_FILES["user_ticket_codepic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/user_ticket/");
				$data["user_ticket_codepic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["user_ticket_nums"]=post("user_ticket_nums");
			$data["user_ticket_sex"]=post("user_ticket_sex");
			$data["user_ticket_age"]=post("user_ticket_age");
			$data["user_ticket_address"]=post("user_ticket_address");
			$data["user_ticket_imei"]=post("user_ticket_imei");
			$data["user_ticket_company"]=post("user_ticket_company");
			$data["user_ticket_company_post"]=post("user_ticket_company_post");
			$data["user_ticket_realname"]=post("user_ticket_realname");
			$data["user_ticket_realname"]=post("user_ticket_realname");
			/* $data["user_ticket_cardtype"]=post("user_ticket_cardtype");
			$data["user_ticket_card"]=post("user_ticket_card"); */
			$data["user_ticket_mobile"]=post("user_ticket_mobile");
			$data["user_ticket_status"]=post("user_ticket_status");
			
			$list=M("user_ticket")->save($data);
			$this->success("修改成功",U('field/user_ticket/user_ticket'));			
		}
		else
		{
			$this->error("不能重复提交",U('field/user_ticket/user_ticket'));
		}

	}

	public function user_ticket_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("user_ticket")->where("user_ticket_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function user_ticket_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_user_ticket set user_ticket_state=1 where user_ticket_id=".$ids_arr[$i]." ");
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

	public function user_ticket_detail()
	{
		if(intval(get("user_ticket_id"))>0)
		{
			$data=M("user_ticket")->where("user_ticket_id=".intval(get("user_ticket_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["user_ticket_name"]."门票领取");
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