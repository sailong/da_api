<?php
/**
 *    #Case		bwvip
 *    #Page		bmw_ticketAction.class.php (门票领取)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class bmw_ticketAction extends piao_publicAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function bmw_ticket()
	{
		$field_uid_sql = '';
		if($_SESSION['piao_admin_id']>1){
			$field_uid_sql = " and field_uid='1186'";
		}
		$list=D("bmw_ticket")->bmw_ticket_list_pro($field_uid_sql);
	
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		

		$this->assign("page_title","门票领取");
    	$this->display();
	}

	public function bmw_ticket_add()
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

	public function bmw_ticket_add_action()
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
			$this->success("添加成功",U('piao/bmw_ticket/bmw_ticket'));
		}
		else
		{
			$this->error("不能重复提交",U('piao/bmw_ticket/bmw_ticket_add'));
		}

	}


	public function bmw_ticket_edit()
	{
		if(intval(get("id"))>0)
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

	public function bmw_ticket_edit_action()
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
			$this->success("修改成功",U('piao/bmw_ticket/user_ticket'));			
		}
		else
		{
			$this->error("不能重复提交",U('piao/bmw_ticket/user_ticket'));
		}

	}

	public function bmw_ticket_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("user_ticket_bmw")->where("id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}
	
	public function bmw_ticket_detail()
	{
		if(intval(get("id"))>0)
		{
			$data=M("user_ticket_bmw")->where("id=".intval(get("id")))->find();
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


	

}
?>