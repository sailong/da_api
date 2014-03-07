<?php
/**
 *    #Case		bwvip.com
 *    #Page		publicAction.class.php (首页)
 *
 *    @author		Jack
 *    @e-mail		zhanglong@bwvip.com
 *    @copyright	www.bwvip.com
 */
class publicAction extends Action
{
	
	public function verify()
	{
        $type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.String");
        import("@.ORG.Image");
        Image::buildImageVerify(4,1,$type,80,40);
    }
	
	public function reg_send_msg()
	{

		$mobile=is_mobile(post('mobile'));
		if($_SESSION['verify']==md5(post("code")))
		{
			if($mobile)
			{
				$smcode=rand(1000,9999);
				$msg_content=''.$smcode.'（大正网 网页版手机注册确认码，请完成验证），如非本人操作，请忽略本短信。【大正】';
				$sql_content=$msg_content;
				$msg_content=iconv('UTF-8', 'GB2312', $msg_content);
				$_SESSION['mobile_verify'] = md5($smcode);//手机发送信息
				echo "succeed^发送成功";
				send_mobile_msg($mobile,$msg_content,$smcode,'reg_from_web',$sql_content);
				
			}
		}
		else
		{
			echo "error^图片验证码输入错误";
			echo post("code");
		}
		
	}
	
	public function reg()
	{

		$this->display();
	}
	
	public function reg_action()
	{
		if(1)//M()->autoCheckToken($_POST)
		{

			$smcode=post("smcode");
			$mobile=is_mobile(post("mobile"));
			if($smcode && $mobile)
			{
				$msg_info=M()->query("select code,msg_task_id from tbl_msg_log where mobile='".$mobile."' and msg_log_source='reg_from_web' order by msg_log_id desc limit 1 ");
				if($msg_info[0]['code']==$smcode)
				{
					M()->query("update tbl_msg_task set msg_task_status=1 where msg_task_id='".$msg_info[0]['msg_task_id']."' ");
					$this->success("手机号验证成功，点击完善用户信息",U('user/public/reg_step2',array('mobile'=>$mobile)));
				}
				else
				{
					$this->error("短信验证码输入有误，请重新输入");
				}
				
			}
			else
			{
				$this->error("手机号和短信验证码均为必填，请正确填写");
			}
			
		}
	
	}
	

	public function reg_step2()
	{
		$this->display();
	}
	
	
	public function reg_step2_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			if($_SESSION['verify']==md5(post("code")))
			{

				$mobile = is_mobile(post('mobile'));
				$username = "W".get_rand_number(10,'0123456789');
				$email = post('email');
				$realname = post('realname');
				$password = trim($_POST['password1']);
				
				$task_info=M()->query("select msg_task_status from tbl_msg_task where mobile='".$mobile."' and msg_task_source='reg_from_web' order by msg_task_id desc limit 1 ");
				if($task_info[0]['msg_task_status']==1)
				{
					if($username && $password)
					{				
						import("@.ORG.UcService");//导入UcService.class.php类   
						$ucService = new UcService;//实例化UcService类   
						$uid = $ucService->register($username, $password, $email);//注册到UCenter   
					}
					
					//如果上面注册成功将返回一个int类型的数字
					if($uid)
					{
						//discuz激活
						$post_string = "&username=".$username."&password=".$_POST['password']."";
						$info = request_by_curl('http://www.bwvip.com/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);
						M()->query("UPDATE ultrax.jishigou_members SET nickname='$realname',validate=1 WHERE ucuid='$uid'"); 
						M()->query("UPDATE pre_common_member_profile SET realname='$realname',mobile='$mobile',cron_fensi_state=0,regdate='".time()."'  WHERE uid='$uid'");
						M()->query("UPDATE pre_common_member SET groupid='10'  WHERE uid='$uid'");
						
						/*
						//注册后自动登录
						$_SESSION['user_id']=intval($uid);
						$_SESSION['user_name']=post("username");
						*/
						
						$this->success('恭喜，注册成功！请登录',U('user/public/login'));
					}
					else
					{   
						$this->error('注册失败----'.$uid,U('user/public/reg'));
					}
					
				
				}
				else
				{
					$this->error("该手机未通过验证，请重新验证",U('user/public/reg'));
				}
			}
			else
			{
				$this->error('验证码错误'.$uid,U('user/public/reg'));
			}
		}
		else
		{   
			$this->error('请不要重复刷新',U('user/public/reg'));   
		} 
		
	}
	
	
	
	
	//青少年注册
	public function reg_qingshaoian()
	{
		$this->display();
	}
	
	
	
	public function login()
	{

		$this->display();
	}
	
	
	
	
	public function login_action()
	{
		if(post('username') && post('password'))
		{
			if($_SESSION['verify']==md5(post("code")))
			{
				
				$mobile=is_mobile(post('username'));
				if($mobile)
				{
					$get_user=M()->query("select uid from pre_common_member_profile where mobile='".$mobile."' order by uid desc limit 1 ");
					if($get_user[0]['uid'])
					{
						$user=M()->query("select uid,username from pre_common_member where uid='".$get_user[0]['uid']."' ");
						if($user[0]['username'])
						{
							$username=$user[0]['username'];
						}
						else
						{
							$username='';
						}
					}
					else
					{
						$username=post('username');
					}
				}
				else
				{
					$username = post('username');
				}

				import("@.ORG.UcService");
				$ucService = new UcService;
				$uidarray = $ucService->uc_login($username, post('password'));
				if($uidarray['uid']>0)
				{
					setcookie(C('UCENTER_COOKIE_NAME'), uc_authcode($uidarray['uid']."\t".post('username'), 'ENCODE'));
				}
				$loginurl=$ucService->uc_synlogin($uidarray['uid']); 
				echo $loginurl;//输出同步登录代码，否则无法同步登录
				
				
				
				if($uidarray['uid']>0)
				{
					/*
					$user_info=M()->query("select * from pre_common_member where uid='".$uidarray['uid']."' "); 
					if(!$user_info[0]['uid'])
					{
						//如果用户不存在，即添加
						$data=array();
						$data['user_id']=$uidarray['uid'];
						$data['uid']=$uidarray['uid'];
						$data['user_name']=$user_info[0]['username'];
						$data['user_password']=md5(post('password'));
						$data['regtime']=time();
						$in = M('user')->add($data);
					}
					*/
					
					
					$_SESSION['user_id']=intval($uidarray['uid']);
					$_SESSION['user_name']=$username;
		
					//echo "登录成功";
					$this->success('登录成功！',U('user/index/index')); 
					

				}
				else
				{
					$this->error('用户名或密码错误',U('user/public/login')); 
				}
			
				
			}
			else
			{
				$this->error('验证码错误'.$uid,U('user/public/login'));
			}
		}
		else
		{
			$this->success('用户名和密码必须填写',U('user/public/login')); 
		}
		
	}



	//青少年第一步信息提交
	public function reg_qingshaonian_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			if($_SESSION['mobile_verify']==md5(post("mobile_verify")))
			{
				$mobile = is_mobile(post('mobile'));
				$parent_mobile = is_mobile(post('parent_mobile'));
				$username = "W".get_rand_number(10,'0123456789');
				$email = post('email');
				$realname = post('realname');
				$password = trim($_POST['password1']);
				
				$task_info=M()->query("select msg_task_status from tbl_msg_task where mobile='".$mobile."' and msg_task_source='reg_from_web' order by msg_task_id desc limit 1 ");
				if($task_info[0]['msg_task_status']==1)
				{
					if($username && $password)
					{				
						import("@.ORG.UcService");//导入UcService.class.php类   
						$ucService = new UcService;//实例化UcService类   
						$uid = $ucService->register($username, $password, $email);//注册到UCenter   
					}
					
					//如果上面注册成功将返回一个int类型的数字
					if($uid)
					{
						//discuz激活
						$post_string = "&username=".$username."&password=".$_POST['password']."";
						$info = request_by_curl('http://www.bwvip.com/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);
						M()->query("UPDATE ultrax.jishigou_members SET nickname='$realname',validate=1 WHERE ucuid=".$uid); 
						M()->query("UPDATE pre_common_member_profile SET realname='".$realname."',mobile='".$mobile."',cron_fensi_state=0,regdate='".time()."'  WHERE uid=".$uid);
						M()->query("UPDATE pre_common_member SET groupid='10'  WHERE uid=".$uid);
						
						/*
						//注册后自动登录
						$_SESSION['user_id']=intval($uid);
						$_SESSION['user_name']=post("username");
						*/
						
						$this->success('恭喜，第一步完成！',U('user/public/reg_qingshaonian_step2',array('mobile'=>$mobile,'parent_mobile'=>$parent_mobile)));
	
					}
					else
					{   
						$this->error('注册失败----'.$uid,U('user/public/reg_qingshaonian'));
					}
					
				
				}
				else
				{
					$this->error("该手机未通过验证，请重新验证",U('user/public/reg_qingshaonian'));
				}
			}
			else 
			{
				$this->error('手机验证码错误',U('user/public/reg_qingshaonian'));
			}
		}
		else
		{   
			$this->error('请不要重复刷新',U('user/public/reg_qingshaonian'));   
		} 
		
	}
	
	//青少年第二步完善
	public function reg_qingshaonian_step2()
	{

		$smcode=rand(1000,9999);
		$msg_content=''.$smcode.'（大正网 网页版手机注册确认码，请完成验证），如非本人操作，请忽略本短信。【大正】';
		$sql_content=$msg_content;
		$msg_content=iconv('UTF-8', 'GB2312', $msg_content);
		$_SESSION['mobile_verify'] = md5($smcode);//手机发送信息
		send_mobile_msg(get('mobile'),$msg_content,$smcode,'reg_from_web',$sql_content);
		//send_mobile_msg(get('parent_mobile'),$msg_content,$smcode,'reg_from_web',$sql_content);

		$this->display();
	}
	
	

	
	//青少年第二步信息提交
	public function reg_qingshaonian_step2_action()
	{
		header("Content-type: text/html; charset=utf-8");
		if(1)//M()->autoCheckToken($_POST)
		{
			$mobile = is_mobile(post('mobile'));
			$parent_mobile = is_mobile(post('parent_mobile'));

			if($_SESSION['mobile_verify']==md5(post("mobile_verify")))
			{
				$username = "W".get_rand_number(10,'0123456789');
				$email = post('email');
				$realname = post('realname');
				$password = trim($_POST['password1']);
				
				$msg_info=M()->query("select code,msg_task_id,msg_task_status from tbl_msg_log where mobile='".$parent_mobile."' and msg_log_source='reg_from_web' order by msg_log_id desc limit 1 ");

				if($msg_info[0]['msg_task_status']==1)
				{
					
					if($username && $password)
					{				
						import("@.ORG.UcService");//导入UcService.class.php类   
						$ucService = new UcService;//实例化UcService类   
						$uid = $ucService->register($username, $password, $email);//注册到UCenter   
					}

					//如果上面注册成功将返回一个int类型的数字
					if($uid)
					{
						//discuz激活
						$post_string = "&username=".$username."&password=".$_POST['password']."";
						$info = request_by_curl('http://a.bwvip.com/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);
						M()->query("UPDATE ultrax.jishigou_members SET nickname='$realname',validate=1 WHERE ucuid=".$uid); 
						M()->query("UPDATE pre_common_member_profile SET realname='".$realname."',mobile='".$parent_mobile."',cron_fensi_state=0,regdate='".time()."'  WHERE uid=".$uid);
						M()->query("UPDATE pre_common_member SET groupid='10'  WHERE uid=".$uid);
						//注册后自动登录
						//$_SESSION['user_id']=intval($uid);
						//$_SESSION['user_name']=post("username");
						
						$this->success('恭喜，第二步完成！',U('user/public/reg_qingshaonian_step3'));

					}
					else
					{   
						$this->error('注册失败----',U('user/public/reg_qingshaonian_step2',array('mobile'=>$mobile,'parent_mobile'=>$parent_mobile)));
					}
				}
				else
				{
					$this->error("该手机用户已存在，请重新验证",U('user/public/reg_qingshaonian_step2',array('mobile'=>$mobile,'parent_mobile'=>$parent_mobile)));
				}
			}
			else
			{
				$this->error('手机验证码错误',U('user/public/reg_qingshaonian_step2',array('mobile'=>$mobile,'parent_mobile'=>$parent_mobile)));
			}
		}
		else
		{   
			$this->error('请不要重复刷新',U('user/public/reg_qingshaonian_step2',array('mobile'=>$mobile,'parent_mobile'=>$parent_mobile)));
		} 
		
	}
	
	public function logout()
	{
		
		import("@.ORG.UcService");
        $ucService = new UcService; 
		$res = $ucService->logout();
		
		unset($_COOKIE);
		unset($_SESSION['user_id']);
		unset($_SESSION['user_name']);
		unset($_SESSION);
		
		$this->success("退出",'/');
		
	}
		
	
}
?>