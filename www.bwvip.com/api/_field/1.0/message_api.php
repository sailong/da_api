<?php
/*
*
* message_api.php
* by zhanglong 2013-05-21
* field app 短消息
*
*/

if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];

//收件箱  /api/field.php?mod=message&ac=list&uid=1&folder=inbox&filter=privatepm
if($ac=="list")
{
	$uid=$_G['gp_uid'];
	$folder=$_G['gp_folder'];  //inbox  outbox  newbox
	$filter=$_G['gp_filter'];  //privatepm   newpm   announcepm
	$page=$_G['gp_page'];
	if(!$page)
	{
		$page=1;
	}
	$page_size=$_G['gp_page_size'];
	if(!$page_size)
	{
		$page_size=10;
	}

	$list=uc_pm_list($uid,$page,$page_size,$folder,$filter,0);
	foreach($list['data'] as $pm)
	{
		//print_r($pm);
		//echo "<hr>";
		$row['pmid']=$pm['pmid'];
		$row['plid']=$pm['plid'];
		$row['uid']=$pm['uid'];
		$row['to_uid']=$pm['touid'];
		$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$pm['to_uid']."&size=small";
		$row['date']=date('Y年m月d日', $pm['dateline']);
		$row['time']=date('H:i', $pm['dateline']);
		$row['message']=$pm['message'];
		$row['realname']=DB::result_first("select realname from ".DB::table("common_member_profile")." where uid='".$row['to_uid']."' ");
		
		$list_data[]=array_default_value($row);
	}
    if(empty($list_data)) {
        $list_data = null;
    }
	$data['title']		= "list_data";
	$data['data']		= $list_data;
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}


//发送消息   /api/field.php?mod=message&ac=send&uid=1&to_uid=3&title=aaaaaaa&content=bbbbbbbbbbbbbbbb
if($ac=="send")
{
	$from_uid=$_G['gp_uid'];
	$to_uid=$_G['gp_send_to_uid'];
	$title=$_G['gp_title'];
	$content=$_G['gp_content'];
	$isusername=0;
	
	if(uc_pm_send($from_uid, $to_uid, $title, $content, 1, 0, $isusername))
	{
		//echo "短消息已发送";
		//检查新消息数
		$msg_num=uc_pm_checknew($from_uid,2);
		$new_pm=$msg_num['newprivatepm'];
		$res=DB::query("update jishigou_members set newpm=newpm+".$new_pm." where uid='".$from_uid."' ");
		$msg_num2=uc_pm_checknew($to_uid,2);
		$new_pm2=$msg_num2['newprivatepm'];
		$res=DB::query("update jishigou_members set newpm=newpm+".$new_pm." where uid='".$to_uid."' ");
		
		api_json_result(1,0,"消息已发送",$data);
	}
	else
	{
		api_json_result(1,1,"消息发送失败",$data);
	}
}


//查看消息  /api/field.php?mod=message&ac=view&uid=1&to_uid=2&pmid=1
if($ac=="view")
{
	$uid = $_G['gp_uid'];
	$to_uid = $_G['gp_to_uid'];
	$pmid = $_G['gp_pmid'];
	if(!$pmid)
	{
		$pmid='';
	}
	$page=$_G['gp_page'];
	if(!$page)
	{
		$page=1;
	}
	$page_size=$_G['gp_page_size'];
	if(!$page_size)
	{
		$page_size=20;
	}

	//print_r($list);
	$list = uc_pm_view($uid,0,$to_uid, 5, $page, $page_size, $type, 1);
	//$data = uc_pm_view($uid,$pmid,$to_uid,1,$page,$page_size,1,1);
	foreach($list as $pm)
	{
		//print_r($pm);
		//echo "<hr>";
		$row['pmid']=$pm['pmid'];
		$row['uid']=$pm['authorid'];
		$row['to_uid']=$pm['touid'];
		$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$pm['uid']."&size=small";
		$row['title']=$pm['subject'];
		$row['content']=$pm['message'];
		$row['date']=date('Y年m月d日', $pm['dateline']);
		$row['time']=date('H:i', $pm['dateline']);
		
		$list_data[]=array_default_value($row);
	}
    if(empty($list_data)) {
        $list_data = null;
    }
	$data['title']		= "list_data";
	$data['data']		= $list_data;
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}


//删除消息 /message.php?ac=delete&uid=1&folder=outbox&pmid=8^
if($ac=="delete")
{
	$uid=$_G['gp_uid'];
	$to_uid=$_G['gp_to_uid'];
	//$folder=$_G['gp_folder'];  //inbox 收件箱  outbox 发件箱
	$pmids=explode("^",$_G['gp_pmids']);
	//$pmids=$_G['gp_pmids'];
	if(uc_pm_delete($uid,'outbox',$pmids) && uc_pm_delete($to_uid,'inbox',$pmids))
	{
		api_json_result(1,0,"删除成功",$data);
	}
	else
	{
		api_json_result(1,1,"删除失败",$data);		
	}
}


//删除会话
if($ac=="delete_huihua")
{
	$uid=$_G['gp_uid'];
	$deluids=explode("^",$_G['gp_deluids']."^".$_G['gp_uid']);
	//$deluids=array(3802649,1);
	//print_r($deluids);
	
	//uc_pm_deleteuser(3802672,array(3802649,3802672),1);
	
	if(uc_pm_deleteuser($uid,$deluids,1))
	{
		api_json_result(1,0,"删除成功",$data);
	}
	else
	{
		api_json_result(1,0,"删除失败",$data);
	}
}



//总经理信箱  /api/field.php?mod=message&ac=ceo_message&uid=1&touid=3802649&field_uid=1186
if($ac=="ceo_message")
{
	$uid=$_G['gp_uid'];
	$to_uid=$_G['gp_field_uid'];
	$page=$_G['gp_page'];
	if(!$page)
	{
		$page=1;
	}
	$page_size=$_G['gp_page_size'];
	if(!$page_size)
	{
		$page_size=10;
	}
	
	$photo=$site_url."/uc_server/avatar.php?uid=".$to_uid."&size=big";

	//消息内容
	//print_r($list);
	$list = uc_pm_view($to_uid,0,$uid,5, $page, $page_size, $type, 0);
	foreach($list as $pm)
	{
		//print_r($pm);
		//echo "<hr>";
		$row['pmid']=$pm['pmid'];
		$row['uid']=$pm['authorid'];
		$row['to_uid']=$pm['touid'];
		$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$pm['authorid']."&size=small";
		$row['title']=$pm['subject'];
		$row['content']=$pm['message'];
		$row['date']=date('Y年m月d日', $pm['dateline']);
		$row['time']=date('H:i', $pm['dateline']);
		
		$list_data[]=array_default_value($row);
	}
    if(empty($list_data))
	{
        $list_data = null;
    }
	$data['title']		= "data";
	$data['data']		= array(
		'photo'=>$photo,
		'list_data'=>$list_data,
	);
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

	
}


function get_plid($uid,$to_uid)
{
	$plid=0;
	$list=uc_pm_list($uid,1,500,'outbox',$filter,0);
	foreach($list['data'] as $pm)
	{
		if($pm['uid']==$uid && $pm['touid']==$to_uid)
		{
			$plid=$pm['pmid'];
			break;
		}
	}
	return $plid;
}

?>