<?php
/**
 *    #Case		bwvip
 *    #Page		User_ticketAction.class.php (门票领取)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class user_ticket_getAction extends AdminAuthAction
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function user_ticket_get()
	{
		$event_id = get('event_id');
		
		$event_id_sql = '';
		if($event_id){
			$event_id_sql = " and event_id='{$event_id}'";
		}
		$event_select=D('event')->event_select_pro(" ");
		$this->assign('event_select',$event_select['item']);
		
		
		$list=D("user_ticket_get")->user_ticket_get_list_pro("{$event_id_sql}");
		
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","订单审核");
    	$this->display();
	}

	/* public function user_ticket_get_add()
	{
	
		$event_id = $_SESSION['event_id'];
		
		$ticket_list = M('ticket')->where("event_id='{$event_id}'")->select();
		
		$this->assign("ticket_list",$ticket_list);
		$this->assign("page_title","添加门票领取");
    	$this->display();
	} */

	// public function user_ticket_get_add_action()
	// {
		// if(M()->autoCheckToken($_POST))
		// {
			
			// //$data["uid"]=post("uid");
			// $ticket_id = $data["ticket_id"]=post("ticket_id");
			
			// $ticket_info = M('ticket')->where("ticket_id='{$ticket_id}'")->find();
			
			// $data["event_id"]=$ticket_info['event_id'];
			// $data["ticket_starttime"]=$ticket_info['ticket_starttime'];
			// $data["ticket_endtime"]=$ticket_info['ticket_endtime'];
			// $data["ticket_times"]=$ticket_info['ticket_times'];
			// $data["user_ticket_code"]=$this->get_randmod_str();
			// $data["ticket_type"]=$ticket_info['ticket_type'];
			// /* if($_FILES["user_ticket_codepic"]["error"]==0)
			// {
				// $uploadinfo=upload_file("upload/user_ticket/");
				// $data["user_ticket_codepic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			// } */
			
			// $data["user_ticket_codepic"] = $this->erweima();
			// $data["user_ticket_nums"]=post("user_ticket_nums");
			// $data["user_ticket_sex"]=post("user_ticket_sex");
			// $data["user_ticket_age"]=post("user_ticket_age");
			// $data["user_ticket_address"]=post("user_ticket_address");
			// $data["user_ticket_imei"]=post("user_ticket_imei");
			// $data["user_ticket_company"]=post("user_ticket_company");
			// $data["user_ticket_company_post"]=post("user_ticket_company_post");
			// $data["user_ticket_realname"]=post("user_ticket_realname");
			// $data["user_ticket_mobile"]=post("user_ticket_mobile");
			// $data["ticket_price"]=post("ticket_price");
			
			// if($ticket_info['ticket_price'] == '0'){
				// $data["user_ticket_status"] = 1;
			// }else{
				// $data["user_ticket_status"]=0;
			// }
			
			// $data["user_ticket_addtime"]=time();
			
			// $pre_member_info = M()->table('pre_common_member_profile')->where("mobile='".$data["user_ticket_mobile"]."'")->find();
			// if($pre_member_info){
				// $data["uid"] = $pre_member_info['uid'];
			// }else{
				// $uid = $this->user_add_return();
				// if(!empty($uid)){
					// $data["uid"] = $uid;
				// }else{
					// $this->success("添加失败",U('admin/user_ticket/user_ticket'));
				// }
			// }
			// $list=M("user_ticket")->add($data);
			// if($list)
			// {
				// if($ticket_info['ticket_price'] == '0'){
					// //添加系统消息
					// $this->sys_message_add_return($data);
				// }
				// $this->success("添加成功",U('admin/user_ticket/user_ticket'));exit;
			// }
			// $this->success("添加失败",U('admin/user_ticket/user_ticket'));
		// }
		// else
		// {
			// $this->error("不能重复提交",U('admin/user_ticket/user_ticket_add'));
		// }

	// }


	public function user_ticket_get_edit()
	{
		if(intval(get("id"))>0)
		{
			$data=M("user_ticket_get")->where("id=".intval(get("id")))->find();
			if(!empty($data))
			{
				$order_ticket_info = explode(',',$data['watch_date']);
				$data['order_detail'] = '';
				$piao_list = array();
				foreach($order_ticket_info as $key=>$val){
					$order_ticket_detail = explode('|',$val);
					$ticket_id = $order_ticket_detail[0];
					$ticket_nums = $order_ticket_detail[1];
					
					$ticket_info = M('ticket')->where("ticket_id='{$ticket_id}'")->find();
					$piao_list[$ticket_id]['piao_id'] = $ticket_info['ticket_id'];
					$piao_list[$ticket_id]['piao_detail'] = $ticket_info['ticket_name'];
					$piao_list[$ticket_id]['order_num'] = $ticket_nums;
					$data['order_list'][$ticket_id] = $piao_list;
					$data['order_detail'] .= $ticket_info['ticket_name'].' '.$ticket_nums.'张<br/>';
				}
				
				$event_info = M('event')->where("event_id='".$data['event_id']."'")->find();
				
				$this->assign("piao_list",$piao_list);
				$this->assign("event_info",$event_info);
				$this->assign("data",$data);
				$this->assign("page_title","订单审核详情");
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

	public function user_ticket_get_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["id"]=post("id");
			$data["family_name"]=post("family_name");
			$data["name"]=post("name");
			$data["phone"]=post("phone");
			$data["address"]=post("address");
			
			$piao_list = $_POST['piao_num_'];
			
			$watch_date = array();
			foreach($piao_list as $key=>$val){
				$watch_date[$key] = $key.'|'.$val;
			}
			$data['watch_date'] = implode(',',$watch_date);
			
			
			$list=M("user_ticket_get")->save($data);
			if($list != false){
				$this->error("修改成功",U('admin/user_ticket_get/user_ticket_get_edit',array('id'=>$data["id"])));
			}
			$this->success("修改失败",U('admin/user_ticket_get/user_ticket_get_edit',array('id'=>$data["id"])));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/user_ticket_get/user_ticket_get_edit',array('id'=>$data["id"])));
		}

	}

	public function user_ticket_get_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("user_ticket_get")->where("id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function user_ticket_get_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			
			foreach($ids_arr as $key1=>$val1)
			{
				//改变审核状态
				$user_ticket_get_info = M('user_ticket_get')->where("id='{$val1}'")->find();
				
				if($user_ticket_get_info !== false){
					//获取订票信息
					if($user_ticket_get_info['phone'] && $user_ticket_get_info['check_status']==0){
						$uid = $this->user_add_return($user_ticket_get_info['phone'],$user_ticket_get_info['email']);
						//user_ticket表添加信息
						$user_ticket_data = array(
							'uid' => $uid,
							'ticket_id' =>'',
							'event_id' => $user_ticket_get_info['event_id'],
							'ticket_type' =>'',
							'ticket_times' =>'',
							'ticket_starttime' =>'',
							'ticket_endtime' =>'',
							'ticket_price' =>'',
							'user_ticket_code' =>'',
							'out_idtype' =>'tbl_user_ticket_get',
							'out_id' =>$val1,
							'user_ticket_codepic' =>'',
							'user_ticket_nums' => 1,
							'user_ticket_realname' => $user_ticket_get_info['family_name'].$user_ticket_get_info['name'],
							//'user_ticket_sex' => ,
							//'user_ticket_age' => ,
							'user_ticket_address' => $user_ticket_get_info['province'].$user_ticket_get_info['city'].$user_ticket_get_info['address'],
							'user_ticket_mobile' => $user_ticket_get_info['phone'],
							'user_ticket_status' => 1,
							'user_ticket_addtime' => time()
						);
						
						$order_ticket_info = explode(',',$user_ticket_get_info['watch_date']);//59|1,60|2,61|3,62|4,63|5,64|6,65|7,66|8,67|9
						foreach($order_ticket_info as $key=>$val){
							$order_ticket_detail = explode('|',$val);
							$ticket_id = $order_ticket_detail[0];
							$ticket_nums = $order_ticket_detail[1];
							$ticket_info = M('ticket')->where("ticket_id='{$ticket_id}'")->find();
							$user_ticket_data['ticket_id'] = $ticket_id;
							$user_ticket_data['ticket_type'] = $ticket_info['ticket_type'];
							$user_ticket_data['ticket_times'] = $ticket_info['ticket_times'];
							$user_ticket_data['ticket_starttime'] = $ticket_info['ticket_starttime'];
							$user_ticket_data['ticket_endtime'] = $ticket_info['ticket_endtime'];
							$user_ticket_data['ticket_price'] = $ticket_info['ticket_price'];
							for($i=0;$i<$ticket_nums;$i++){
								$user_ticket_data['user_ticket_code'] = $this->get_randmod_str();
								$user_ticket_data['user_ticket_codepic'] = $this->erweima();
								$rs = M('user_ticket')->add($user_ticket_data);
								if($rs){
									$this->sys_message_add_return($user_ticket_data);
								}
								$user_ticket_ids[] = $rs;
							}
						}
						
					}
					$res=M()->execute("update tbl_user_ticket_get set check_status=1,user_ticket_id='{$rs}',user_ticket_ids='".implode(",",$user_ticket_ids)."' where id='{$val1}'");
				}
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


	public function user_ticket_get_detail()
	{
		if(intval(get("id"))>0)
		{
			$data=M("user_ticket_get")->where("id=".intval(get("id")))->find();
			if(!empty($data))
			{
				$order_ticket_info = explode(',',$data['watch_date']);
				$data['order_detail'] = '';
				$piao_list = array();
				foreach($order_ticket_info as $key=>$val){
					$order_ticket_detail = explode('|',$val);
					$ticket_id = $order_ticket_detail[0];
					$ticket_nums = $order_ticket_detail[1];
					
					$ticket_info = M('ticket')->where("ticket_id='{$ticket_id}'")->find();
					$piao_list[$ticket_id]['piao_id'] = $ticket_info['ticket_id'];
					$piao_list[$ticket_id]['piao_detail'] = $ticket_info['ticket_name'];
					$piao_list[$ticket_id]['order_num'] = $ticket_nums;
					$data['order_list'][$ticket_id] = $piao_list;
					$data['order_detail'] .= $ticket_info['ticket_name'].' '.$ticket_nums.'张<br/>';
				}
				
				$event_info = M('event')->where("event_id='".$data['event_id']."'")->find();
				
				$this->assign("piao_list",$piao_list);
				$this->assign("event_info",$event_info);
				$this->assign("data",$data);
				$this->assign("page_title","订单审核详情");
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
	
	/* //根据赛事id(event_id)获取相关赛事门票列表
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
	} */
	
	//生成二维码成功返回路径，失败返回 false
	public function erweima()
	{
		$phone = time().mt_rand(1000000000,9999999999);
		//如果没有就生成二维码
		$path_erweima_core = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
		
		require_once($path_erweima_core."/tool/phpqrcode/qrlib.php");
		$prefix = $path_erweima_core;
		$save_path="/upload/erweima/";
		$now_date = date("Ymd",time());
		$full_save_path=$path_erweima_core.$save_path.$now_date."/";

		if(!file_exists($prefix.$save_path))
		{
			mkdir($prefix.$save_path);
		}
		if(!file_exists($full_save_path))
		{
			$a = mkdir($full_save_path);
		}
		
		$pic_filename=$full_save_path.$phone.".png";
		$sql_save_path = $save_path.$now_date.'/'.$phone.".png";
		$errorCorrectionLevel = "L";
		$matrixPointSize=9;
		$margin=1;
		
		QRcode::png($phone, $pic_filename, $errorCorrectionLevel, $matrixPointSize, $margin); 
		
		if(file_exists($pic_filename))
		{
			return $sql_save_path;
		}
		else
		{
			return false;
		}
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
	
	
	/*
	*  添加用户注册
	*/
	public function user_add_return($phone,$email)
	{
		
		if(!empty($phone))
		{
			$rs=M()->table('pre_common_member_profile')->where(" mobile='{$phone}'")->find();
			if(!empty($rs)){
				return $rs['uid'];
			}
		}
		$rand_nums = time(). mt_rand(1000,9999);
		$user_data["username"]=$phone;
		$password='123456';
		$salt = substr(uniqid(rand()), -6);
		$password = md5(md5($password).$salt);
		$user_data["salt"]=$salt;
		$user_data["password"]=$password;
		$user_data["email"]=$email; 
		$user_data["mobile"]=$phone; 
		$user_data["regip"]=time();
		$user_data["regdate"]=time();
		//生成ucenter会员 
		$list=M("ucenter_members","pre_")->add($user_data); 
		$ucuid=$list;
		unset($data["salt"]);
		$user_data["groupid"]=10;  
		//生成社区会员 
		$list=M("common_member","pre_")->add($user_data); 
		
		$user_data["uid"]=$ucuid; 
		$user_data["gender"]=''; 
		$user_data["realname"]=$rand_nums;	
		
		//生成真实姓名
		$list=M("common_member_profile","pre_")->add($user_data); 
		$user_data["nickname"]=$rand_nums;
		$user_data["ucuid"]=$ucuid; 
		$user_data["role_id"]=3; 			
		
		//生成微博记录
		$list=M("members","jishigou_")->add($user_data); 
		
		if($list!=false)
		{
			return $ucuid;
		}
		else
		{
			return false;
		}
	}
	
	public function sys_message_add_return($user_ticket_info)
	{
/* 		{
		  message_id: "4",
		  uid: "1000139",
		  message_title: "测试消息",
		  message_pic: "",
		  message_addtime: "1374825755",
		  pic_width: "",
		  pic_height: "",
		  message_info: {
			n_title: "测试消息",
			n_content: "测试消息测试消息测试消息",
			n_extras: {
			  action: "system_msg"
			}
		  },
		  message_sendtime: "2013-07-26"
		},
 */		
		$sys_event_id = $user_ticket_info['event_id'];
		
		$sys_event_info = M('event')->where("event_id='{$sys_event_id}'")->find();
		$sys_field_uid=$sys_event_info['field_uid'];
		if(empty($sys_field_uid)){
			$sys_field_uid = 0;
		}
		//$max=M()->query("select max(message_number) as max_id from tbl_sys_message where message_type='".post("message_type")."'  ");
		//$data["message_number"]=$max[0]['max_id']+1;
		//$data["message_type"]=post("message_type");
		$sys_data["field_uid"]=$sys_field_uid;
		if($user_ticket_info["uid"])
		{
			$sys_uid=$user_ticket_info["uid"];
			//$is_push=M()->query("select if_push from pre_common_member_profile where uid='".$uid."' ");
		}
		else
		{
			$sys_uid=0;
		}
		$sys_data["uid"]=$sys_uid;
		$sys_data["message_title"]=$sys_event_info['event_name']."门票申请成功";//post("message_title");

		$n_title=$sys_data["message_title"];
		$n_content=$sys_data["message_title"];
		
		$message_extinfo=array('action'=>"system_msg");	
		
		$msg_content = json_encode(array('n_title'=>urlencode($n_title), 'n_content'=>urlencode($n_content),'n_extras'=>$message_extinfo));

		$sys_data["message_content"]=$msg_content;
		$sys_data["receiver_type"]=3;//3:指定用户
		$sys_data['message_pic']=$user_ticket_info['user_ticket_codepic'];
		
	
		$sys_data["message_state"]=0;
		$sys_data["message_totalnum"]=0;
		$sys_data["message_sendnum"]=0;
		$sys_data["message_errorcode"]="";
		$sys_data["message_errormsg"]="";
		$sys_data["message_addtime"]=time();
		
		$list=M("sys_message")->add($sys_data);

		if($list!=false)
		{
			return true;
		}
		else
		{				
			return false;
		}
	
	}

}
?>