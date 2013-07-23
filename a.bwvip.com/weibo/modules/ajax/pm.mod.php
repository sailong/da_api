<?php

/*******************************************************************

 * [JishiGou] (C)2005 - 2099 Cenwor Inc.

 *

 * This is NOT a freeware, use is subject to license terms

 *

 * @Package JishiGou $

 *

 * @Filename pm.mod.php $

 *

 * @Author http://www.jishigou.net $

 *

 * @Date 2011-09-28 19:16:47 1755940853 1114908473 13719 $

 *******************************************************************/




/**
 * 文件名：topic.mod.php
 * 版本号：1.0
 * 最后修改时间：2009年9月28日 14时10分42秒
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 微博话题AJAX模块
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	var $TopicLogic;

	var $ID;


	function ModuleObject($config)
	{
		$this->MasterObject($config);

		$this->initMemberHandler();

		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);

		$this->ID = (int) ($this->Post['pmid'] ? $this->Post['pmid'] : $this->Get['pmid']);


		$this->Execute();
	}

	
	function Execute()
	{
		switch($this->Code)
		{
			case 'send':
				$this->Send();
				break;

			case 'do_add':
				$this->DoAdd();
				break;
			case 'delete':
				$this->DoPmDelete();
				break;
			case 'view_comment':
				$this->ViewComment();
				break;
			case 'listchat':
				$this->ListChat();
				break;
			case 'pm_follow_user':
				$this->Pm_Follow_User();
				break;
				
			case 'pm_group_list':
				$this->PmGroupList();
				break;
				
			default:
				$this->Main();
				break;
		}

		exit;
	}

	function Main()
	{
		response_text("正在建设中……");
	}

	function Send() {
		$to_user = $this->Post['to_user'];
		if (empty($to_user)) {
			$to_user = $this->Get['to_user'];
		}
		ob_start();
		include($this->TemplateHandler->Template('pm_send_ajax'));
		response_text(ob_get_clean());
	}

	    function DoAdd()
	{
        if(MEMBER_ID < 1)
        {
            exit('请先登录或者注册一个帐号');
        }
        
		$this->noticeConfig = ConfigHandler::get('email_notice');

				if($this->MemberHandler->HasPermission($this->Module,$this->Code)==false)
		{
			exit($this->MemberHandler->GetError());
		}

		$to_user_list=array();
		filter($this->Post['message'],'',false,true);
		$this->Post['subject']=htmlspecialchars(trim($this->Post['subject']));
		if($this->Post['message']=='')
		{
			exit("内容不能为空");
		}
		if ($this->Post['buddy_list']==false && $this->Post['to_user']=="")
		{
			exit("收件人不能为空");
		}


		if(trim($this->Post['to_user'])!='')
		{

			$in=$this->DatabaseHandler->BuildIn($this->Post['to_user'],"nickname");
						$sql="
			SELECT
				uid,nickname,notice_pm,email,newpm
			FROM
				".TABLE_PREFIX.'members'."
			WHERE
				$in";
			$query = $this->DatabaseHandler->Query($sql);

			while($row=$query->GetRow())
			{
				$to_user_list[$row['uid']]=$row;
			}
		}
		$to_user_list+=(array)$this->Post['buddy_list'];
		if($to_user_list==false)
		{
			exit("收件人不存在");
		}
				
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'pms');
		foreach($to_user_list as $to_user_id => $to_user_name)
		{
			$data=array(
			"msgfrom"		=>MEMBER_NAME,
			"msgnickname"		=>MEMBER_NICKNAME,
			"msgfromid" => MEMBER_ID,  						"msgtoid"   => $to_user_id,						"subject"   => "",
			"message"   => $this->Post['message'],
			"new"=>'1',
			"dateline"=>time(),
			);

			if($this->Post["save_to_outbox"])
			{
				$data['folder']="outbox";
				$msg="消息已经保存草稿箱";
			}
			$this->DatabaseHandler->Insert($data);
			$pmid = $this->DatabaseHandler->Insert_ID();
		}
				$sql = "update `".TABLE_PREFIX."pms` set `topmid`= '{$this->Post['topmid']}'  where `pmid` = {$pmid}";
		$this->DatabaseHandler->Query($sql);


				$_tmps=array_keys($to_user_list);
		$to_user_id_list = array();
		foreach($_tmps as $_tmp) {
			$_tmp = (int) $_tmp;
			if($_tmp > 0) {
				$to_user_id_list[$_tmp] = $_tmp;
			}
		}
		$num=$this->Post["save_to_outbox"]?2:1;
		$this->UpdateNewMsgCount($num,$to_user_id_list);

		

		foreach ($to_user_list as $user_notice)
		{
			 if($user_notice['notice_pm'] == 1)  			 {
					if($this->Config['notice_email'] == 1) 					{
						Load::lib('mail');
						$mail_to = $user_notice['email'];

						$mail_subject = "{$this->Config[site_name]}邮件提醒";
						$pm_content = '您有'.$user_notice['newpm'].'条站内短信没有查看。';
						$send_result = send_mail($mail_to,$mail_subject,$mail_content,array(),3,false);

												$sql = "update `".TABLE_PREFIX."members` set `last_notice_time`= time()  where `uid` = {$user_notice['uid']}";
						$this->DatabaseHandler->Query($sql);
					}
					else
					{
												Load::logic('notice');
						$NoticeLogic = new NoticeLogic();
						$pm_content = $user_notice['nickname'].':您有'.$user_notice['newpm'].'条站内短信没有查看，'.$this->Config[site_url].'/index.php?mod=pm&code=list';
						$NoticeLogic->Insert_Cron($user_notice['uid'],$user_notice['email'],$pm_content,'pm');

					}
				}
		}
		

     exit($msg);
	}


	function DoPmDelete()
	{

			$folder=$this->Post['act'];
			$msg_field = $folder == 'inbox' ? 'msgtoid' : 'msgfromid';
			$folderadd = $folder == 'track' ? "AND folder='inbox' AND new>'0'" : "AND folder='$folder'";
			$deleteadd = $folder == 'inbox' ? "AND delstatus = '1'" : ($folder == 'track' ? "AND delstatus = '2'" : '');
			$deletestatus = $folder == 'inbox' ? 2 : ($folder == 'track' ? 1 : 0);

			if($pmids = $this->DatabaseHandler->BuildIn($this->ID,'')) {

			$this->DatabaseHandler->Query("DELETE FROM ".TABLE_PREFIX.'pms'." WHERE $msg_field=".MEMBER_ID." AND pmid IN ($pmids) $folderadd $deleteadd", 'mysql_unbuffered_query');
			if($deleteadd) {
			$this->DatabaseHandler->Query("UPDATE ".TABLE_PREFIX.'pms'." SET delstatus='$deletestatus' WHERE $msg_field=".MEMBER_ID." AND pmid IN ($pmids)", 'mysql_unbuffered_query');
			}
			} else {
			$this->DatabaseHandler->Query("DELETE FROM ".TABLE_PREFIX.'pms'." WHERE $msg_field=".MEMBER_ID." AND pmid='$pmid' $folderadd $deleteadd", 'mysql_unbuffered_query');
			if($deleteadd) {
			$this->DatabaseHandler->Query("UPDATE ".TABLE_PREFIX.'pms'." SET delstatus='$deletestatus' WHERE $msg_field=".MEMBER_ID." AND pmid='$pmid'", 'mysql_unbuffered_query');
			}
		}

	}


	function ViewComment()
	{
		$folder = $this->Post['cod'];
		$people=$folder=='inbox'?"来自":"发送到";

		$uid = MEMBER_ID;

		$uid_field=$folder=='inbox'?'P.msgtoid='.MEMBER_ID:'P.msgfromid='.MEMBER_ID;
		$folder_field=$folder!='outbox'?" AND P.folder='inbox'":" AND P.folder='outbox'";
		$delstatus_field=$folder=='inbox'?" AND P.delstatus!='2'":($folder=='track'?" AND P.delstatus!='1'":'');

		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'pms');
		define('QUERY_SAFE_DACTION_3', true);
		$sql="
		 (SELECT
			 P.*,M.username as msgto
		  FROM
			 ".TABLE_PREFIX.'pms'." P LEFT JOIN ".TABLE_PREFIX.'members'." M ON(M.uid=P.msgtoid)
		  WHERE
			P.pmid<{$this->ID} AND $uid_field $folder_field $delstatus_field
		  ORDER BY
			  P.pmid DESC
		  LIMIT 1)
		  UNION
			 (SELECT
				P.*,M.username as msgto
			 FROM
			   ".TABLE_PREFIX.'pms'." P LEFT JOIN ".TABLE_PREFIX.'members'." M ON(M.uid=P.msgtoid)
			 WHERE
				 P.pmid>={$this->ID} AND $uid_field $folder_field $delstatus_field
			 ORDER BY
				 P.pmid ASC
			 LIMIT 2)";
		$query = $this->DatabaseHandler->Query($sql);

		while($row=$query->GetRow())
		{
			$row['id']=$row['pmid'];

			if($row['id'] < $this->ID)
			{
				$prev_id = $row['id'];
				continue;
			}
			if($row['id']==$this->ID)
			{
				$row['user']=$folder!="inbox"?$row['msgto']:$row['msgfrom'];
				$row['user_id']=$folder!="inbox"?$row['msgtoid']:$row['msgfromid'];
				$pm_info=$row;

				continue;
			}
			if($row['id']>$this->ID)$next_id=$row['id'];
		}

		if($pm_info==false)
		{
			exit("短信不存在或您无查看他人短信");
		}

		if($pm_info["new"]==1 && !($pm_info['msgfromid'] == MEMBER_ID && $pm_info['msgtoid'] != MEMBER_ID && $pm_info['folder'] == 'inbox'))
		{
			$this->DatabaseHandler->Update(array("new"=>"0","pmid"=>$this->ID));
			$this->Stat['inbox_unread_count']>0 && $this->Stat['inbox_unread_count']--;
			$this->MemberHandler->MemberFields['newpm']>0 && $this->MemberHandler->MemberFields['newpm']--;
			$this->UpdateNewMsgCount('-1',MEMBER_ID);
		}

				if ($pm_info!=false)
		{
			if ($pm_info['msgfromid'])
			{
				if (true===UCENTER_FACE)
				{
					$query = $this->DatabaseHandler->Query("SELECT `uid`,`ucuid`,`face_url`,`face` FROM ".TABLE_PREFIX.'members'." WHERE uid=".$pm_info['msgfromid']);
					$_row = $query->GetRow();
					$pm_info['face'] = face_get($_row);
				}
				else
				{
					$pm_info['face'] = face_get($pm_info['msgfromid']);
				}
			}
		}
		
		$pm_info['message'] = process_url($pm_info['message']);

		extract($pm_info,EXTR_SKIP);
		$message = $pm_info['message'];

		$nickname = $pm_info['msgnickname'] ? $pm_info['msgnickname'] : $pm_info['msgfrom'];

		$send_time=my_date_format($pm_info['dateline']);

		$this->Title=$subject;

		$action="index.php?mod=pm&code=dosend";
		$to_user=$user;
		$mod_link=array('name'=>"站内短信",'url'=>"?mod=pm");
		$folder_link=array('name'=>$folder_name,'url'=>"?mod=pm");

		if($pm_info['topmid'])
		{
				$wherelist = "and `pmid` = '{$pm_info['topmid']}' ";
		}

		$sql = "select * from `".TABLE_PREFIX."pms` where `msgfromid` = '{$uid}' and `msgtoid` = '{$pm_info['msgfromid']}' {$wherelist} order by `dateline` desc limit 0,1";

		$query = $this->DatabaseHandler->Query($sql);
		$listchat = array();
		while($row = $query->GetRow())
		{
			$row['message'] = process_url($row['message']);
			
			$listchat[] = $row;
		}
		

		include $this->TemplateHandler->Template('pm_view_comment_ajax');

	}

	function ListReply($tid=0,$highlight=0)
	{
		;
	}




		function UpdateNewMsgCount($num,$uids='')
	{
		if($uids=='')$uids=MEMBER_ID;
		
		$uids=$this->DatabaseHandler->BuildIn($uids,'uid');
		if(!$uids) return ;
		
		$strpos = strpos($num,'-');
		if($strpos!==0)
		{
			$num="+".$num;
		}
		
		$sql="
		UPDATE
			".TABLE_PREFIX.'members'."
		SET
			newpm=newpm $num
		WHERE
			$uids";
			
		$this->DatabaseHandler->Query($sql);
		
		$ret = $this->DatabaseHandler->AffectedRows();;

		if(0 === $strpos) 
		{
			$this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set `newpm`=0 where $uids and `newpm`<0");
		}
		
		return $ret;
	}
	
		function Pm_Follow_User() 
	{
		
	 			$sql = "select * from `".TABLE_PREFIX."buddys` where `uid` = '".MEMBER_ID."'";
		$query = $this->DatabaseHandler->Query($sql);
		$uids = array();
		while ($row = $query->GetRow())
		{
			$uids[$row['buddyid']] = $row['buddyid'];
		}
	
			    $page = empty($this->Get['page']) ? 0 : intval($this->Get['page']);
	
		$perpage = 9;
		
		if ($page == 0) {
			$page = 1;
		}
		
		$start = ($page - 1) * $perpage;
		
		$uid = MEMBER_ID;
		
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('members')." WHERE `uid` in ('".implode("','",$uids)."') ");
		
		if($count)
		{
			$members = $this->TopicLogic->GetMember("where `uid` in ('".implode("','",$uids)."') LIMIT $start,$perpage","`uid`,`ucuid`,`username`,`fans_count`,`validate`,`province`,`city`,`face`,`nickname`");
			
			
			$member_list = array();
			foreach ($members as $_m) {
				
				$member_list[$_m['uid']]['face'] = $_m['face'];
				$member_list[$_m['uid']]['uid'] = $_m['uid'];
		
				$member_list[$_m['uid']]['nickname'] = $_m['nickname'];
			}
			
			$page_html = ajax_page($count, $perpage, $page, 'PmFollowUserDialog');
		}
	   include $this->TemplateHandler->Template('pm_follow_user_ajax');
	}
	
		function PmGroupList() 
	{	
		$uid = (int) $this->Post['uid'];
		$gid = (int) $this->Post['group_id'];
		
		$sql="select `id`,`uid`,`gid`,`touid` from ".TABLE_PREFIX.'groupfields'." where `gid` = '{$gid}' and `uid` = '{$uid}' ";
		$query = $this->DatabaseHandler->Query($sql);
		$my_groupuids = array();
        while ($row = $query->GetRow()) {
        	
        	$my_groupuids[$row['touid']] = $row['touid'];
        }
        
        	    $page = empty($this->Post['page']) ? 0 : intval($this->Get['page']);
	
		$perpage = 9;
		
		if ($page == 0) {
			$page = 1;
		}
		
		$start = ($page - 1) * $perpage;
		
		$uid = MEMBER_ID;
		
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('groupfields')." WHERE `gid` = '{$gid}' and `uid` = '{$uid}' ");
		
        if($my_groupuids)
        {
       		$members = $this->TopicLogic->GetMember("where `uid` in ('".implode("','",$my_groupuids)."') LIMIT $start,$perpage","`uid`,`ucuid`,`username`,`fans_count`,`validate`,`province`,`city`,`face`,`nickname`");
			
			
			$member_list = array();
			foreach ($members as $_m) {
				
				$member_list[$_m['uid']]['face'] = $_m['face'];
				$member_list[$_m['uid']]['uid'] = $_m['uid'];
		
				$member_list[$_m['uid']]['nickname'] = $_m['nickname'];
			}
			
			$page_html = ajax_page($count, $perpage, $page, 'pm_group_list',$gid);
        }
        
                $sql="select `uid`,`id`,`group_name` from ".TABLE_PREFIX.'group'." where `id` = '{$gid}' and `uid` = '{$uid}' ";
		$query = $this->DatabaseHandler->Query($sql);
        $row = $query->GetRow();
        
        $group_name = $row['group_name'];
       
        
        include $this->TemplateHandler->Template('pm_follow_user_ajax');
     
	}

}

?>
