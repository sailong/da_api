<?php 

define('APPTYPEID', 65);
define('CURSCRIPT', 'myindex');

require_once './source/class/class_core.php';
require_once './source/function/function_home.php';

$discuz = & discuz_core::instance(); 
$discuz->init(); 

$mobile =$_G ['gp_m']; //手机号
$r =$_G ['gp_r']?$_G ['gp_r']:$mobile; //注册用手机号 


$s =$_G ['gp_s'];//验证吗
$c =$_G ['gp_c'];//查询申请次数 1，当天 2，全部
$sm =$_G ['gp_sm'];//手机号


if($mobile){

 
	if(checkm($mobile))
	{ 
	  echo '10109';//已经注册
	}else{		
         
		//$corp_service= YYS($mobile);
  
		 
		if(checkex($mobile))
		{
			update($mobile,$smcode);
			//已经发送过的还按原来的验证码发送
			$smcode =checkex($mobile);
			$msg_content='您的验证码为：'.$smcode.' [大正]';
			$msg_content=iconv('UTF-8', 'GB2312', $msg_content);;
		}
		else
		{
		
			//生成验证码
			$smcode=rand(1000,9999);
			
			$msg_content='您的验证码为：'.$smcode.' [大正]';
			$msg_content=iconv('UTF-8', 'GB2312', $msg_content);;
			
			insert($mobile,$smcode,$r);
		} 
			 
		// $post_string = "corp_id=$corp_id&corp_pwd=$corp_pwd&corp_service=$corp_service&mobile=$mobile&msg_content=$msg_content";
		$t = DB::result_first( "select count(0)  from " . DB::table ( 'tmsg' ) . "  where rmobile='$r' ");
		if($t<5){	
			//$ret=request_by_curl('http://211.103.155.246:8080/sms_send2.do',$post_string); 
			echo sendmsg($mobile,$msg_content);
		
		
		}else
		{
			$ret='10103';
		}
		if($ret=='0#1')
		{
			
		}else{
		
			 echo $ret;
		
		}
	}
} 


if($sm&&$s){
	
	if(checkm($sm))
	{ 
	  echo '10109';//已经注册
	}else{		
         
	if(checksm($sm,$s))	
		{		
		  echo '0';//通过
		} 
		
	 if(checksm($sm,$s)==-1)
		{ 
		  echo '10102';//手机号错误
		}
		  
		  if(checksm($sm,$s)==0)
		{ 
		  echo '10101';//验证码错误
		}  
	}
}
if($c&&$s){ 
 if($c==2)
  {
	$cs = DB::result_first("select num  from " . DB::table ( 'tmsg' ) . "  where mobile='$s' ");
 }else if($c==1)
 {
	$cs = DB::result_first("select num0  from " . DB::table ( 'tmsg' ) . "  where mobile='$s' ");
 }else{
	$cs = DB::result_first("select count(1)  from " . DB::table ( 'tmsg' ) . "  where rmobile='$s' ");
 }
 echo $cs;
}
	
/**
手机号状态
*/
function checkm($mobile){
	
	$state = DB::result_first( "select username  from " . DB::table ( 'common_member' ) . "  where username='$mobile' ");
   //return $state;r
   return false;
}

function checkex($mobile){
	
	$state = DB::result_first( "select smcode  from " . DB::table ( 'tmsg' ) . "  where mobile='$mobile' ");
     return $state;
}

function checksm($mobile,$smcode){
	
	$state = DB::result_first( "select count(0)  from " . DB::table ( 'tmsg' ) . "  where mobile='$mobile' ");
	if($state)
	{
	$state = DB::result_first( "select count(0)  from " . DB::table ( 'tmsg' ) . "  where mobile='$mobile' and smcode='$smcode' ");
			if($state)
			{
			   return $state;
			}
			else
			{
				$state = 0;
				}
	}
	else
	{
		$state = -1;
	
	}
     return $state;
}

/*
更新状态
*/

function  update($mobile,$smcode){
 
	$showtime = time();
	//$sql="update " . DB::table ( 'tmsg' ) . " set num=num+1, dateline='$showtime' , smcode='$smcode'  where mobile='$mobile'";
	$sql="update " . DB::table ( 'tmsg' ) . " set num=num+1,  num0=num0+1, dateline='$showtime'  where mobile='$mobile'";
	$re=DB::query($sql); 

}

/**
插入记录
*/
function insert($mobile,$smcode,$r){
	

	$arr ['mobile'] = $mobile;
	$arr ['rmobile'] = $r;
	$arr ['smcode'] = $smcode;	
	$arr ['smcode'] = $smcode;
	$arr ['dateline']  = time();
	$arr ['addtime']  = time();
	$row = DB::insert('tmsg', $arr);	
	
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


function sendmsg($mobile,$content)
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
			
?>
