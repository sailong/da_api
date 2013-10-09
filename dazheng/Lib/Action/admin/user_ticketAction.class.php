<?php
/**
 *    #Case		bwvip
 *    #Page		User_ticketAction.class.php (门票领取)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class user_ticketAction extends AdminAuthAction
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function user_ticket()
	{
		$event_id = get('event_id');
		$ticket_id = get('ticket_id');
		$event_id_sql = '';
		if($event_id){
			$event_id_sql = " and event_id='{$event_id}'";
		}
		if($ticket_id){
			$event_id_sql .= " and ticket_id='{$ticket_id}'";
		}
		
		$user_ticket_status = get('user_ticket_status');
		$user_ticket_status_sql = '';
		
		if($user_ticket_status !== '')
		{
			$user_ticket_status_sql = " and user_ticket_status='{$user_ticket_status}'";
		}
		
		$event_select_tmp=D('event')->event_select_pro(" ");
		$event_select = array();
		foreach($event_select_tmp['item'] as $key=>$val)
		{
			$event_select[$val['event_id']] = $val;
		}
		
		unset($event_select_tmp);
		$this->assign('event_select',$event_select);
		
		//$event_id = $_SESSION['event_id'];
		$price = get('price');
		$price_sql = '';
		if($price == 'free'){
			$price_sql = " and ticket_price='0'";
		}elseif($price == 'no_free'){
			$price_sql = " and ticket_price!='0'";
		}
		
		//$list=D("user_ticket")->user_ticket_list_pro(" and event_id='{$event_id}' {$price_sql}");
		$list=D("user_ticket")->user_ticket_list_pro("{$event_id_sql}{$price_sql}{$user_ticket_status_sql}");
		//$ticket_lists = M('ticket')->where("event_id='{$event_id}'")->select();
		$ticket_lists = M('ticket')->select();
		
		foreach($list["item"] as $key=>$val)
		{
			$ticket_ids[$val['ticket_id']] = $val['ticket_id'];
		}
		
		$ticket_list = M('ticket')->where("ticket_id in('".implode("','",(array)$ticket_ids)."')")->select();
		
		foreach($ticket_list as $key=>$val)
		{
			unset($ticket_list[$key]);
			$ticket_list[$val['ticket_id']] = $val['ticket_name'];
		}
		$this->assign("ticket_lists",$ticket_lists);
		$this->assign("ticket_list",$ticket_list);
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","门票领取");
    	$this->display();
	}

	public function user_ticket_add()
	{
	
		$event_id = $_SESSION['event_id'];
		
		$ticket_list = M('ticket')->where("event_id='{$event_id}'")->select();
		
		$this->assign("ticket_list",$ticket_list);
		$this->assign("page_title","添加门票领取");
    	$this->display();
	}

	public function user_ticket_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			
			//$data["uid"]=post("uid");
			$ticket_id = $data["ticket_id"]=post("ticket_id");
			
			$ticket_info = M('ticket')->where("ticket_id='{$ticket_id}'")->find();
			
			$data["event_id"]=$ticket_info['event_id'];
			$data["ticket_starttime"]=$ticket_info['ticket_starttime'];
			$data["ticket_endtime"]=$ticket_info['ticket_endtime'];
			$data["ticket_times"]=$ticket_info['ticket_times'];
			$data["user_ticket_code"]=$this->get_randmod_str();
			$data["ticket_type"]=$ticket_info['ticket_type'];
			/* if($_FILES["user_ticket_codepic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/user_ticket/");
				$data["user_ticket_codepic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			} */
			
			$data["user_ticket_codepic"] = $this->erweima();
			$data["user_ticket_nums"]=post("user_ticket_nums");
			$data["user_ticket_sex"]=post("user_ticket_sex");
			$data["user_ticket_age"]=post("user_ticket_age");
			$data["user_ticket_address"]=post("user_ticket_address");
			$data["user_ticket_imei"]=post("user_ticket_imei");
			$data["user_ticket_company"]=post("user_ticket_company");
			$data["user_ticket_company_post"]=post("user_ticket_company_post");
			$data["user_ticket_realname"]=post("user_ticket_realname");
			$data["user_ticket_mobile"]=post("user_ticket_mobile");
			$data["ticket_price"]=post("ticket_price");
			
			if($ticket_info['ticket_price'] == '0'){
				$data["user_ticket_status"] = 1;
			}else{
				$data["user_ticket_status"]=0;
			}
			
			$data["user_ticket_addtime"]=time();
			
			$pre_member_info = M()->table('pre_common_member_profile')->where("mobile='".$data["user_ticket_mobile"]."'")->find();
			if($pre_member_info){
				$data["uid"] = $pre_member_info['uid'];
			}else{
				$uid = $this->user_add_return($data["user_ticket_realname"],$data["user_ticket_mobile"]);
				if(!empty($uid)){
					$data["uid"] = $uid;
				}else{
					$this->success("添加失败",U('admin/user_ticket/user_ticket'));
				}
			}
			$list=M("user_ticket")->add($data);
			if($list)
			{
				if($ticket_info['ticket_price'] == '0'){
					//添加系统消息
					$this->sys_message_add_return($data);
				}
				$this->success("添加成功",U('admin/user_ticket/user_ticket'));exit;
			}
			$this->success("添加失败",U('admin/user_ticket/user_ticket'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/user_ticket/user_ticket_add'));
		}

	}


	public function user_ticket_edit()
	{
		if(intval(get("user_ticket_id"))>0)
		{
			$data=M("user_ticket")->where("user_ticket_id=".intval(get("user_ticket_id")))->find();
			$this->assign("data",$data);
			
			$event_id = get('event_id');
			$ticket_list = M('ticket')->where("event_id='{$event_id}'")->select();
			
			$this->assign("ticket_list",$ticket_list);
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
			$data["ticket_type"]=$ticket_info['ticket_type'];
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
	
			$data["user_ticket_mobile"]=post("user_ticket_mobile");
			$data["user_ticket_status"]=post("user_ticket_status");
			$data["ticket_price"]=post("ticket_price");
			
			$list=M("user_ticket")->save($data);
			if($list){
				if($data["user_ticket_status"] == '1'){
					$this->sys_message_add_return($data);
				}
			}
			$this->success("修改成功",U('admin/user_ticket/user_ticket'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/user_ticket/user_ticket'));
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
			
			foreach($ids_arr as $key=>$val)
			{
				$res=M()->execute("update tbl_user_ticket set user_ticket_status='1' where user_ticket_id='{$val}'");
				if($res !== false){
					$user_ticket_info = M('user_ticket')->where("user_ticket_id='{$val}'")->find();
					if(!empty($user_ticket_info)){
						$this->sys_message_add_return($user_ticket_info);
					}
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

	public function user_ticket_detail()
	{
		if(intval(get("user_ticket_id"))>0)
		{
			$data=M("user_ticket")->where("user_ticket_id=".intval(get("user_ticket_id")))->find();
			if(!empty($data))
			{
				$ticket_info = M('ticket')->where("ticket_id='".$data['ticket_id']."'")->find();
				
				$event_info = M('event')->where("event_id='".$data['event_id']."'")->find();
				
				$this->assign("event_info",$event_info);
				$this->assign("ticket_info",$ticket_info);
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
	
	public function user_ticket_detail_ext()
	{
		if(intval(get("out_id"))>0 || get("out_idtype") != '')
		{
			$table_name = get("out_idtype");
			$data=M()->table($table_name)->where("id=".intval(get("out_id")))->find();
			
			if(!empty($data))
			{
				$order_ticket_info = explode(',',$data['watch_date']);
				$data['order_detail'] = '';
				foreach($order_ticket_info as $key=>$val){
					$order_ticket_detail = explode('|',$val);
					$ticket_id = $order_ticket_detail[0];
					$ticket_nums = $order_ticket_detail[1];
					$ticket_info = M('ticket')->where("ticket_id='{$ticket_id}'")->find();
					$data['order_detail'] .= $ticket_info['ticket_name'].' '.$ticket_nums.'张<br/>';
				}
				$event_info = M('event')->where("event_id='".$data['event_id']."'")->find();
				
				$this->assign("event_info",$event_info);
				$this->assign("data",$data);

				$this->assign("page_title","附加信息");
				$this->display($table_name);
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
	
	
	//生成二维码成功返回路径，失败返回 false
	public function erweima()
	{
		$phone = mt_rand(1000000000,9999999999);
		//如果没有就生成二维码
		$path_erweima_core = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
		
		include $path_erweima_core."/tool/phpqrcode/qrlib.php";
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
	public function user_add_return($username,$phone,$email)
	{
		
		if(!empty($phone))
		{
			$rs=M()->table('pre_common_member_profile')->where("mobile='{$phone}'")->order('uid desc')->find();
			if(!empty($rs)){
				return $rs['uid'];
			}
		}
		if(empty($username)){
			$username = time(). mt_rand(1000,9999);
		}
		
		 
		$user_data["username"]=$username;
		$password_tmp = $password=mt_rand(100000,999999);
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
		$user_data["realname"]=$username;	
		
		//生成真实姓名
		$list=M("common_member_profile","pre_")->add($user_data); 
		$user_data["nickname"]=$username;
		$user_data["ucuid"]=$ucuid; 
		$user_data["role_id"]=3; 			
		
		//生成微博记录
		$list=M("members","jishigou_")->add($user_data); 
		
		if($list!=false)
		{
			//发送短信
			if($phone){
				$msg_content="您的门票已购买成功并成为大正网用户,请您下载并登录大正网客户端 个人中心，我的门票中查看具体信息。您的大正登录名为:".$phone."，密码为:".$password_tmp."，大正客户端下载地址：http://www.bwvip.com/app ";
				$msg_content=iconv('UTF-8', 'GB2312', $msg_content);;
				$this->send_msg($phone,$msg_content);
			}
			
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
	
	//发送手机短信
	public function send_msg($mobile,$content)
	{
		
		$start=file_get_contents("msg.txt");
		file_put_contents("msg.txt",$start+1);	
		$flag = 0; 
				//要post的数据 
		$argv = array( 
			 'sn'=>'SDK-BBX-010-16801', ////替换成您自己的序列号
			 'pwd'=>strtoupper(md5('SDK-BBX-010-16801'.'f-_4ef-4')), //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
			 'mobile'=>$mobile,//手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
			 'content'=>$content,//短信内容
			 'ext'=>'',		
			 'stime'=>date("Y-m-d H:i:s"),//定时时间 格式为2011-6-29 11:09:21
			 'rrid'=>''
			 ); 
		//构造要post的字符串 
		foreach ($argv as $key=>$value) {
			if ($flag!=0) { 
						 $params .= "&"; 
						 $flag = 1; 
			} 
			$params.= $key."="; $params.= urlencode($value); 
			$flag = 1; 
		} 
		$length = strlen($params); 
				 //创建socket连接 
		$fp = fsockopen("sdk2.zucp.net",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
		//构造post请求的头 
		$header = "POST /webservice.asmx/mt HTTP/1.1\r\n"; 
		$header .= "Host:sdk2.zucp.net\r\n"; 
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
		$header .= "Content-Length: ".$length."\r\n"; 
		$header .= "Connection: Close\r\n\r\n"; 
		//添加post的字符串 
		$header .= $params."\r\n"; 
		//发送post的数据 
		fputs($fp,$header); 
		$inheader = 1; 
		while (!feof($fp)) { 
			$line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据 
			if ($inheader && ($line == "\n" || $line == "\r\n")) { 
				$inheader = 0; 
			} 
			if ($inheader == 0) { 
				// echo $line; 
			} 
		} 
		//<string xmlns="http://tempuri.org/">-5</string>
		$line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
		$line=str_replace("</string>","",$line);
		$result=explode("-",$line);
		// echo $line."-------------";
		  
		if(count($result)>1)
		return $line;
		else
		return '0#1';
	}
}
?>