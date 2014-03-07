<?php
define('APPTYPEID', 65);
define('CURSCRIPT', 'myindex');

require_once './source/class/class_core.php';
require_once './source/function/function_home.php';

$discuz = & discuz_core::instance(); 
$discuz->init(); 



//第一步发布手机号
$mobile =$_G ['gp_m']; //手机号
$r =$_G ['gp_r']?$_G ['gp_r']:$mobile; //用户本身的手机号 

if($mobile==13731583787 or $mobile==15852280092 or $mobile==13632484714 or $mobile==13600253198 or $mobile==15018666669 or $mobile==15052842998  or $mobile==15052842998  or $mobile==15165804377  or $mobile==13036306660   or $mobile==13830714389   or $mobile==13599986997   or $mobile==13390793024 or $mobile==18668190081  or $mobile==18628110404)
{
	exit;
}

$time=time();
if($mobile)
{
	if(check_reg($mobile))
	{
		echo '10109';//已经注册
	}
	else
	{
		$smcode=rand(1000,9999);
		$msg_content='您的验证码为：'.$smcode.' ,如收到多个短信，请以最后为准。【大正】';
		$sql_content=$msg_content;
		$msg_content=iconv('UTF-8', 'GB2312', $msg_content);
		
		
		//检查是否已发送过验证码，如果有，则读取旧的验证码
		$task_info = DB::fetch_first( "select * from tbl_msg_task where mobile='".$mobile."' and msg_task_status=0 and msg_task_source='reg' order by msg_task_id desc ");
		if(!$task_info['msg_task_id'])
		{
			//如果没有任务，则添加
			$task_in="insert into tbl_msg_task (field_uid,mobile,msg_task_source,msg_task_status,msg_task_addtime,msg_task_date) values ('".$field_uid."','".$mobile."','reg','0','".$time."','".date("Y-m-d H:i:s",$time)."') ";
			DB::query($task_in);
			$msg_task_id = DB::result_first( "select msg_task_id from tbl_msg_task where mobile='".$mobile."' and msg_task_status=0 and msg_task_source='reg' order by msg_task_id desc limit 1 ");
		}
		else
		{
			$msg_task_id=$task_info['msg_task_id'];
		}
		
		
		if(!$task_info['msg_task_lasttime'])
		{
			//第一次发送
			echo send_msg($mobile,$msg_content,$smcode,'reg',$sql_content,$msg_task_id);
		}
		else if($_SERVER['REQUEST_TIME']-$task_info['msg_task_lasttime']>60 && $task_info['msg_task_success_num']<3 )
		{
			//第二次，如符合条件即发送
			echo send_msg($mobile,$msg_content,$smcode,'reg',$sql_content,$msg_task_id);
		}
		else
		{
			//发送失败
			$ret='10103';
			echo $ret;
		}
		
		
	}

		
}


//第二步验证 验证码
$sm =$_G ['gp_sm'];//接收验证码的手机号
$s =$_G ['gp_s'];//接收的验证码
$c =$_G ['gp_c'];//查询申请次数 1，当天 2，全部   （可能无用）

if($sm && $s)
{
	
	if(check_reg($sm))
	{ 
		echo '10109';//已经注册
	}
	else
	{		
         
		$status=check_code($sm,$s);
		if($status)
		{
			//把所属验证码设置为已使用状态
			DB::query("update tbl_msg_task set msg_task_status=1 where mobile='".$sm."' ");
			echo '0';//通过
		} 
		
		if($status==-3)
		{ 
			echo '10102';//手机号错误
		}
		  
		if($status==-2)
		{ 
			echo '10101';//验证码错误
		}
		
	}
}


//检查发送次数
if($c&&$s)
{
	if($c==2)
	{
		$cs = DB::result_first("select num  from " . DB::table ( 'tmsg' ) . "  where mobile='$s' ");
	}
	else if($c==1)
	{
		$cs = DB::result_first("select num0  from " . DB::table ( 'tmsg' ) . "  where mobile='$s' ");
	}
	else
	{
		$cs = DB::result_first("select count(1)  from " . DB::table ( 'tmsg' ) . "  where rmobile='$s' ");
	}
	echo $cs;
}




/**
手机号状态
*/
function check_reg($mobile)
{
	$uid = DB::result_first( "select uid from ".DB::table('common_member_profile'). "  where mobile='$mobile' ");
	if($uid)
	{
		return true;
	}
	else
	{
		return false;
	}
	
}


function check_code($mobile,$smcode)
{
	
	$send_info = DB::fetch_first( "select msg_log_status,code,mobile from tbl_msg_log where mobile='$mobile' order by msg_log_id desc ");
	if(!$send_info['msg_log_id'])
	{
		if($send_info['code']!=$smcode)
		{
			//验证码不对
			$status=-2;
		}
		else
		{
			$status=$send_info['msg_log_status'];
		}
		
	}
	else
	{
		//手机号不对
		$status=-3;
	}
	
    return $status;
}




 /*  if(data=='0#1')
{alert('发送成功！');}
else if(data=='10109')
{alert('已经注册！');}
else if(data=='-1')
{alert('手机号错误！');}
else if(data=='10103')
{alert('此手机号注册次数过多！');} */
//改demo的功能是群发短信和发单条短信。（传一个手机号就是发单条，多个手机号既是群发）

//您把序列号和密码还有手机号，填上，直接运行就可以了

//如果您的系统是utf-8,请转成GB2312 后，再提交、
//请参考 'content'=>iconv( "UTF-8", "gb2312//IGNORE" ,'您好测试短信[XXX公司]'),//短信内容
$m=$_GET['m'];


function send_msg($mobile,$content,$code,$source,$sql_content,$msg_task_id)
{
	//判断手机号合法性
	preg_match_all("/1[3|5|8][0-9]{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|18[0|5|6|7|8|9]\d{8}/",$mobile, $if_mobile);
	if(!empty($if_mobile[0]))
	{
		$ip=_get_client_ip();
		$sql_send_num=$send_num+1;
		//LOG记录
		$log_sql="insert into tbl_msg_log (mobile,code,content,msg_log_source,msg_log_addtime,msg_log_date,ip,msg_task_id) values ('".$mobile."','".$code."','".$sql_content."','".$source."','".time()."','".date("Y-m-d H:i:s",time())."','".$ip."','".$msg_task_id."') ";
		DB::query($log_sql);
		$new_log_id = DB::result_first( "select msg_log_id from tbl_msg_log where mobile='".$mobile."' order by msg_log_id desc limit 1 ");


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
			DB::query($log_up_sql);
			
			$task_up_sql="update tbl_msg_task set msg_task_lasttime='".$_SERVER['REQUEST_TIME']."',msg_task_err_num=msg_task_err_num+1 where msg_task_id='".$msg_task_id."' ";
			DB::query($task_up_sql);
			
			
			return $line;
			
			//line  -4代表没费了
		}
		else
		{
			//如果发送成功，更新状态
			$log_up_sql="update tbl_msg_log set msg_log_sendtime='".$_SERVER['REQUEST_TIME']."',msg_log_status=1 where msg_log_id='".$new_log_id."' ";
			DB::query($log_up_sql);
			
			$task_up_sql="update tbl_msg_task set msg_task_lasttime='".$_SERVER['REQUEST_TIME']."',msg_task_success_num=msg_task_success_num+1 where msg_task_id='".$msg_task_id."' ";
			DB::query($task_up_sql);
			
			return '0#1';
		}
	}
	else
	{
		return '-5';
	}

	
}




function _get_client_ip()
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



/*
$ip=_get_client_ip();
//echo $ip;
$timetest=time(); $timestr=strftime("%H:%M:%S",$timetest); 
$str = $timestr.":".$_SERVER["REQUEST_URI"]."   IP:".$ip."\r\n"; 
$k=fopen("ip.txt","a+");//此处用a+，读写方式打开，将文件指针指向文件末尾。如果文件不存在则
fwrite($k,$str);
fclose($k);

*/
			
?>
