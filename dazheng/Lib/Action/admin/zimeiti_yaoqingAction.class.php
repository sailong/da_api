<?php
/**
 *    #Case		bwvip
 *    #Page		zimeiti_applyAction.class.php 第一线申请管理
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2014-02-13
 */
class zimeiti_yaoqingAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function zimeiti_yaoqing()
	{
		//球场列表
		$field_select_tmp=D('field')->field_select_pro(" ");
		//var_dump($field_select_tmp);
		$field_select = array();
		foreach($field_select_tmp['item'] as $key=>$val){
			$field_select[$val['field_uid']] = $val['field_name'];
		}
		//var_dump($field_select);
		$this->assign('field_select',$field_select);
	
		$page_size=get('page_size');
		if(!$page_size)
		{
			$page_size=20;
		}
	
		$list=D("zimeiti_yaoqing")->zimeiti_yaoqing_list_pro('',$page_size,' zimeiti_yaoqing_addtime desc ');
		
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","第一线邀请管理");
    	$this->display();
	}

	public function zimeiti_yaoqing_add()
	{
		//球场列表
		$field_select=D('field')->field_select_pro(" ");
		$this->assign('field_select',$field_select['item']);
		
		$this->assign("page_title","添加报名");
    	$this->display();
	}
	
	public function zimeiti_yaoqing_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			
			$data["uid"]=post("uid");
			$data["field_uid"]=post("field_uid");
			$data["to_uid"]=post("to_uid");
			$data["mobile"]=post("mobile");
			$data["guanxi"]=post("guanxi");
			$data["zimeiti_yaoqing_status"]=post("zimeiti_yaoqing_status");
			$data["zimeiti_yaoqing_addtime"]=time();
			
			$list=M("zimeiti_yaoqing")->add($data);
			
			$this->success("添加成功",U('admin/zimeiti_yaoqing/zimeiti_yaoqing'));
			
		}
		else
		{
			$this->error("不能重复提交",U('admin/zimeiti_yaoqing/zimeiti_yaoqing_add'));
		}

	}


	public function zimeiti_yaoqing_edit()
	{
		if(intval(get("zimeiti_yaoqing_id"))>0)
		{
			//球场列表
			$field_select=D('field')->field_select_pro(" ");
			$this->assign('field_select',$field_select['item']);
			
			$data=M("zimeiti_yaoqing")->where("zimeiti_yaoqing_id=".intval(get("zimeiti_yaoqing_id")))->find();
			
			$this->assign("data",$data);
			
			$this->assign("page_title","修改");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}
	
	public function zimeiti_yaoqing_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{	
			$data["zimeiti_yaoqing_id"]=post("zimeiti_yaoqing_id");
			
			$data["uid"]=post("uid");
			$data["field_uid"]=post("field_uid");
			$data["to_uid"]=post("to_uid");
			$data["mobile"]=post("mobile");
			$data["guanxi"]=post("guanxi");
			$data["zimeiti_yaoqing_status"]=post("zimeiti_yaoqing_status");
			//$data["zimeiti_yaoqing_addtime"]=time();
			
			$list=M("zimeiti_yaoqing")->save($data);
			$this->success("修改成功",U('admin/zimeiti_yaoqing/zimeiti_yaoqing'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/zimeiti_yaoqing/zimeiti_yaoqing'));
		}

	}
	
	
	public function zimeiti_yaoqing_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("zimeiti_yaoqing")->where("zimeiti_yaoqing_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function zimeiti_yaoqing_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_zimeiti_yaoqing set zimeiti_yaoqing_state=1 where zimeiti_yaoqing_id=".$ids_arr[$i]." ");
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

	public function zimeiti_yaoqing_detail()
	{
		if(intval(get("zimeiti_yaoqing_id"))>0)
		{
			$data=M("zimeiti_yaoqing")->where("zimeiti_yaoqing_id=".intval(get("zimeiti_yaoqing_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				//$this->assign("page_title",$data["zimeiti_apply_name"]."报名");
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
	
	
	public function sys_message_add_return($info_arr)
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
		
		//$sys_field_uid=$info_arr['field_uid'];
		//if(empty($sys_field_uid)){
		//	$sys_field_uid = 0;
		//}
		//$max=M()->query("select max(message_number) as max_id from tbl_sys_message where message_type='".post("message_type")."'  ");
		//$data["message_number"]=$max[0]['max_id']+1;
		//$data["message_type"]=post("message_type");
		$sys_data["field_uid"]=0;//$sys_field_uid;
		if($info_arr["uid"]!='')
		{
			$sys_uid=$info_arr["uid"];
			//$is_push=M()->query("select if_push from pre_common_member_profile where uid='".$uid."' ");
		}
		else
		{
			$sys_uid=0;
		}
		$sys_data["uid"]=$sys_uid;
		$sys_data["message_title"]=$info_arr['message_title'];//"系统消息";//post("message_title");

		$n_title=$sys_data["message_title"];
		$n_content=$info_arr['message_content'];//;'恭喜您已成为第一线用户，账号是您的手机号,密码是随机号,请登录客户端修改密码！';
		
		$message_extinfo=array('action'=>"system_msg");	
		
		$msg_content = json_encode(array('n_title'=>urlencode($n_title), 'n_content'=>urlencode($n_content),'n_extras'=>$message_extinfo));

		$sys_data["message_content"]=$msg_content;
		$sys_data["receiver_type"]=3;//3:指定用户
		//$sys_data['message_pic']=$info_arr['pic'];
		
	
		$sys_data["message_state"]=0;
		$sys_data["message_totalnum"]=0;
		$sys_data["message_sendnum"]=0;
		$sys_data["message_errorcode"]="";
		$sys_data["message_errormsg"]="";
		$sys_data["message_addtime"]=time();
		$list=M("sys_message")->add($sys_data);
		/* if($list!=false)
		{
			return true;
		}
		else
		{				
			return false;
		} */
		return ;
	
	}
	
	//发送手机短信
	/* 
	$msg_content="您的门票已购买成功并成为大正网用户,请您下载并登录大正网客户端 个人中心，我的门票中查看具体信息。您的大正登录名为:".$phone."，密码为:".$password_tmp."，大正客户端下载地址：http://www.bwvip.com/app ";
	$msg_content=iconv('UTF-8', 'GB2312', $msg_content);;
	$this->send_msg($phone,$msg_content); */
	public function send_msg($mobile,$content,$code,$source,$sql_content,$msg_task_id)
	{
		//判断手机号合法性
		/* echo $mobile.'--mobile<br>';
		echo $content.'--content<br>';
		echo $code.'--code<br>';
		echo $source.'--source<br>';
		echo $sql_content.'--sql_content<br>';
		echo $msg_task_id.'--msg_task_id<br>';die; */
		$rs = preg_match_all("/1[3|5|8][0-9]{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|18[0|5|6|7|8|9]\d{8}/",$mobile, $if_mobile);
		/* var_dump($rs);
		var_dump($if_mobile);die; */
		if($rs)
		{
			$ip=get_client_ip();
			$sql_send_num=$send_num+1;
			$time = time();
			$msg_log_data['mobile'] = $mobile;
			$msg_log_data['code'] = $code;
			$msg_log_data['content'] = $sql_content;
			$msg_log_data['msg_log_source'] = $source;
			$msg_log_data['msg_log_addtime'] = $time;
			$msg_log_data['msg_log_date'] = date('Y-m-d H:i:s',$time);
			$msg_log_data['ip'] = $ip;
			$msg_log_data['msg_task_id'] = $msg_task_id;
			$new_log_id = M('msg_log')->add($msg_log_data);
			/* $start=file_get_contents("msg.txt");
			file_put_contents("msg.txt",$start+1);	 */
			$flag = 0; 
			//要post的数据 
			$argv = array( 
				 'sn'=>'SDK-BBX-010-16801', ////替换成您自己的序列号
				 'pwd'=>strtoupper(md5('SDK-BBX-010-16801'.'f-_4ef-4')), //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
				 'mobile'=>$mobile,//手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
				 'content'=>$content,//短信内容
				 'ext'=>'',		
				 'stime'=>date('Y-m-d H:i:s',$time),//定时时间 格式为2011-6-29 11:09:21
				 'rrid'=>''
				 ); 
				 
			//构造要post的字符串 
			foreach ($argv as $key=>$value)
			{ 
				if($flag!=0)
				{ 
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
			
			
			while (!feof($fp))
			{ 
				$line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据 
				if($inheader && ($line == "\n" || $line == "\r\n"))
				{ 
					$inheader = 0; 
				} 
				if ($inheader == 0)
				{
					// echo $line; 
				} 
			}
			
			//<string xmlns="http://tempuri.org/">-5</string>
			$line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
			$line=str_replace("</string>","",$line);
			$result=explode("-",$line);
				  // echo $line."-------------";
				   
			if(count($result)>1)
			{
				//如果发送不成功，更新状态
				
				if($line==-4)
				{
					$msg_log_status=-4;
				}
				else
				{
					$msg_log_status=-1;
				}
			
				$log_up_sql="update tbl_msg_log set msg_log_sendtime='".$_SERVER['REQUEST_TIME']."',msg_log_status='".$msg_log_status."' where msg_log_id='".$new_log_id."' ";
				M()->query($log_up_sql);
				
				$task_up_sql="update tbl_msg_task set msg_task_lasttime='".$_SERVER['REQUEST_TIME']."',msg_task_err_num=msg_task_err_num+1 where msg_task_id='".$msg_task_id."' ";
				M()->query($task_up_sql);
				
				return $line;
				
				//line  -4代表没费了
			}
			else
			{
				//如果发送成功，更新状态
				$log_up_sql="update tbl_msg_log set msg_log_sendtime='".$_SERVER['REQUEST_TIME']."',msg_log_status=1 where msg_log_id='".$new_log_id."' ";
				
				M()->query($log_up_sql);
				
				$task_up_sql="update tbl_msg_task set msg_task_lasttime='".$_SERVER['REQUEST_TIME']."',msg_task_success_num=msg_task_success_num+1 where msg_task_id='".$msg_task_id."' ";
				
				M()->query($task_up_sql);
				
				return '0#1';
			}
		}
		else
		{
			return '-5';
		}

		
	}
	
	public function get_client_ip()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		if(isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches))
		{
			foreach ($matches[0] AS $xip)
			{
				if(!preg_match('#^(10|172\.16|192\.168)\.#', $xip))
				{
					$ip = $xip;
					break;
				}
			}
		}
		return $ip;
	}
}
?>