<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename pm.mod.php $ 
 * 
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-21 14:57:42 1144246587 1320121120 21539 $
 *******************************************************************/



if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	
	var $Code = array();

	
	var $ID = 0;
	
	
	var $Stat=array();
	
	var $Folder='inbox';
	
	var $FolderName='';


	
	var $IDS;

	
	function ModuleObject($config)
	{
		$this->MasterObject($config);

		if(isset($this->Get['code']))
		{
			$this->Code = $this->Get['code'];
		}elseif(isset($this->Post['code']))
		{
			$this->Code = $this->Post['code'];
		}

		if(isset($this->Get['id']))
		{
			$this->ID = (int)$this->Get['id'];
		}elseif(isset($this->Post['id']))
		{
			$this->ID = (int)$this->Post['id'];
		}

		if(isset($this->Get['ids']))
		{
			$this->IDS = $this->Get['ids'];
		}elseif(isset($this->Post['ids']))
		{
			$this->IDS = $this->Post['ids'];
		}
		$_GET['rmod']='my';
		
		if (MEMBER_ID < 1) {
			$this->Messager("请先登录",null);
		}
		
		$this->FolderList=array("inbox"=>"收件箱",'outbox'=>"草稿箱",'track'=>"已发送");
		$folder=trim($this->Post['folder']?$this->Post['folder']:$this->Get['folder']) or $folder='inbox';
		$this->Folder=($this->FolderName=$this->FolderList[$folder])?$folder:'inbox';
		
		

		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();
		switch($this->Code)
		{
			case 'list':
				$this->PmList();
				break;
			case 'linkman_list':
				$this->LinkmanList();
				break;
			case 'delete_linkman':
				$this->DeleteLinkman();
				break;

			case 'send':
								$this->Send();
				break;

			case 'dosend':
				$this->DoSend();
				break;

			case 'markunread':
				$this->MarkUnread();
				break;

			case 'delete':
			case 'dodelete':
				$this->DoDelete();
				break;

			case 'view':
				$this->View();
				break;

			default:
				$this->PmList();
				break;
		}
		$Contents=ob_get_clean();
		$this->ShowBody($Contents);
	}

	function _member($uid) {
		Load::logic('topic');
		$TopicLogic = new TopicLogic($this);
		
		$member = $TopicLogic->GetMember($uid);
        

		return $member;
	}

	function PmList()
	{
		$member = $this->_member(MEMBER_ID);
		$topic_selected = 'pm';
		
		if($member['newpm'])
		{
						$sql = "update `".TABLE_PREFIX."members` set `newpm`=0 where `uid`='{$member['uid']}'";
			$this->DatabaseHandler->Query($sql);
			$this->MemberHandler->MemberFields['newpm'] = 0;
		}	
				$per_page_num = 20;	
		
		$folder=$this->Folder;
		$this->Title=$folder_name=$this->FolderName;		
		$people=$folder=='inbox'?"来自":"发送到";
		
		
		if($folder == 'inbox')
		{

			$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&code={$this->Code}&folder=inbox" : "");	
			
						$sql="SELECT  count(*) as `total_record` from ".TABLE_PREFIX.'pms'." WHERE msgtoid =".MEMBER_ID." $filter AND folder='inbox' AND delstatus!='2' ORDER BY dateline DESC  ";
			$query = $this->DatabaseHandler->Query($sql);
			extract($query->GetRow());
		
		  			$page_arr = page($total_record,$per_page_num,$query_link,array('return'=>'array',));
		
			$filter=trim($this->Get['filter'])=='newpm'?"AND new>0":'';
			$sql="SELECT * from ".TABLE_PREFIX.'pms'." WHERE msgtoid =".MEMBER_ID." $filter AND folder='inbox' AND delstatus!='2' ORDER BY dateline DESC  {$page_arr['limit']} ";
			$query = $this->DatabaseHandler->Query($sql);	
			
		}
		elseif($folder=='outbox')
		{
			$sql="SELECT p.*, m.username AS msgto FROM ".TABLE_PREFIX.'pms'." p LEFT JOIN ".TABLE_PREFIX.'members'." m ON m.uid=p.msgtoid WHERE p.msgfromid=".MEMBER_ID." AND p.folder='outbox' ORDER BY p.dateline DESC";
			$query = $this->DatabaseHandler->Query($sql);
		}
		elseif ($folder=='track')
		{
			
			$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&code={$this->Code}&folder=track" : "");	
			
						$sql="SELECT  count(*) as `total_record` from ".TABLE_PREFIX.'pms'." WHERE msgfromid=".MEMBER_ID." AND folder='inbox' AND delstatus!='1'";
			$query = $this->DatabaseHandler->Query($sql);
			extract($query->GetRow());
		
		  			$page_arr = page($total_record,$per_page_num,$query_link,array('return'=>'array',));
	
		
			$sql="SELECT COUNT(*) track_count FROM ".TABLE_PREFIX.'pms'." WHERE msgfromid=".MEMBER_ID." AND folder='inbox' AND delstatus!='1'";
			$query = $this->DatabaseHandler->Query($sql);
			 
			$this->Stat['track_count']=($row=$query->GetRow())?$row['track_count']:0;
			$sql="SELECT p.*, m.`uid`,`nickname`,`username`, m.uid AS msgto FROM ".TABLE_PREFIX.'pms'." p LEFT JOIN ".TABLE_PREFIX.'members'." m ON m.uid=p.msgtoid WHERE p.msgfromid=".MEMBER_ID." AND p.folder='inbox' AND delstatus!='1' ORDER BY p.dateline DESC {$page_arr['limit']}";
			$query = $this->DatabaseHandler->Query($sql);
		
		}
		
		$pm_list=array();

		while($row=$query->GetRow())
		{	
			
			$row['send_time']=my_date_format($row['dateline'],"Y-m-d H:i");
			$row['user']=$folder!="inbox" ? $row['msgto']:$row['msgfrom'];
			$row['user_id']=$folder!="inbox"?$row['msgtoid']:$row['msgfromid'];
			
			$row['style']=$row['new']!="0"?' class="style08"':'';
			$row['id']=$row['pmid'];

			$row['nickname'] = $row['nickname'];
			
			$row['message'] = cut_str($row['message'],50);
			
			if('inbox' == $folder)
			{
				$row['nickname'] = $row['msgnickname'];
			}
		
			if('track' == $folder and $row['is_hi'])
			{ 
				$row['user_id'] = '';
				$row['user'] = '<span style="color:green">匿名会员</span>';
			}
			
			$pm_list[]=$row;
		}
		
	
	
		$to_user=$this->Get['touser'];
				$left_menu=$this->LeftMenu();
		$mod_link=array('name'=>"站内短信",'url'=>"?mod=pm");
		$folder_link=array('name'=> $folder_name,'url'=>"?mod=pm");

		include $this->TemplateHandler->Template('pm_list');

	}



	
	function Send()
	{	
		
		
		$member = $this->_member(MEMBER_ID);
		$topic_selected = 'pm';
		
		$this->Title='发送新消息';
		$action="index.php?mod=pm&code=dosend";
		$to_user=$this->Get['to_user']?$this->Get['to_user']:$this->Post['to_user'];
		$subject=$this->Get['subject']?$this->Get['subject']:$this->Post['subject'];
	
		if($this->ID!=1)
		{
			$sql="select msgtoid,msgfrom,subject,message from ".TABLE_PREFIX.'pms'." where pmid =".$this->ID;
			$query = $this->DatabaseHandler->Query($sql);
			$pm=$query->GetRow();
			
			if ($pm!=false) 
			{
				$to_user = $pm['msgfrom'];
				$subject="回复:".$pm['subject'];
				$pm['message'] = $pm['message'];
			}
		}
		$left_menu=$this->LeftMenu();

				$sql="select `id`,`uid`,`group_name`,`group_count` from ".TABLE_PREFIX.'group'." where `uid` = '".MEMBER_ID."' ";
		$query = $this->DatabaseHandler->Query($sql);
		$my_grouplist = array();
        while ($row = $query->GetRow()) {
        	
        	$my_grouplist[] = $row;
        }

		include $this->TemplateHandler->Template('pm_write');
	}

	
	function DoSend()
	{	
		if(MEMBER_ID < 0)
		{
		  $this->Messager("请先登录在操作",'index.php?mod=login');
		}		
		$this->noticeConfig = ConfigHandler::get('email_notice');
		
				if($this->MemberHandler->HasPermission($this->Module,$this->Code)==false)
		{
			$this->Messager($this->MemberHandler->GetError(),null);
		}
			
		$to_user_list=array();
		filter($this->Post['message'],'',false,true);
		$this->Post['subject']=htmlspecialchars(trim($this->Post['subject']));
		if($this->Post['message']=='')
		{
			$this->Messager("内容不能为空",-1);
		}
		if ($this->Post['buddy_list']==false && $this->Post['to_user']=="")
		{
			$this->Messager("收件人不能为空",-1);
		}
		
		
		if(trim($this->Post['to_user'])!='')
		{
		
			$in=$this->DatabaseHandler->BuildIn($this->Post['to_user'],"nickname");
						$sql="
			SELECT 
				uid,username,nickname,notice_pm,email,newpm
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
			$this->Messager("收件人不存在",-1);
		}
						
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'pms');
		foreach($to_user_list as $to_user_id => $to_user_name)
		{
			$data=array(
			"msgfrom"		=>MEMBER_NAME,
			"msgnickname"		=>MEMBER_NICKNAME,
			"msgfromid" => MEMBER_ID,  						"msgtoid"   => $to_user_id,						"subject"   => $this->Post['subject'],
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
					

			
		}
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
			
						$mail_subject = "{$this->noticeConfig['pm']['title']}";
						$mail_content = "{$this->noticeConfig['pm']['content']}";
						$send_result = send_mail($mail_to,$mail_subject,$mail_content,array(),3,false);
						
												$sql = "update `".TABLE_PREFIX."members` set `last_notice_time`= time()  where `uid` = {$user_notice['uid']}";
						$this->DatabaseHandler->Query($sql);
					}
					else
					{
												
						Load::logic('notice');
						$NoticeLogic = new NoticeLogic();
						$pm_content = '您有'.$user_notice['newpm'].'条站内短信没有查看，请立即查看。';
						$NoticeLogic->Insert_Cron($user_notice['uid'],$user_notice['email'],$pm_content,'pm');
						
					}
				}

				
			if($this->Config['imjiqiren_enable'] && imjiqiren_init($this->Config))
			{
				imjiqiren_send_message($user_notice,'m',$this->Config);
			}
				
			if($this->Config['sms_enable'] && sms_init($this->Config))
			{
				sms_send_message($user_notice,'m',$this->Config);
			}
		}
		
		if($this->Config['extcredits_enable'] && MEMBER_ID > 0)
		{
			
			update_credits_by_action('pm',MEMBER_ID,count($to_user_list));
		}

		
			
  	$this->Messager(NULL,"index.php?mod=pm&type=list");
	}

	function GetMsgSendForm($ToUser="",$Subject="",$Message="")
	{
		ob_start();
		include $this->TemplateHandler->Template('form_pm_send');
		Return ob_get_clean();
	}



	
	function DoDelete()
	{
		$folder=$this->Folder;
		
		$msg_field = $folder == 'inbox' ? 'msgtoid' : 'msgfromid';
		$folderadd = $folder == 'track' ? "AND folder='inbox' AND new>'0'" : "AND folder='$folder'";
		$deleteadd = $folder == 'inbox' ? "AND delstatus = '1'" : ($folder == 'track' ? "AND delstatus = '2'" : '');
		$deletestatus = $folder == 'inbox' ? 2 : ($folder == 'track' ? 1 : 0);

		if($pmids = $this->DatabaseHandler->BuildIn($this->IDS,'')) {
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
		$this->Messager("消息删除成功","index.php?mod=pm&code=list&folder={$folder}");
	}


	
	function LinkmanList()
	{
		$sql="
		  SELECT
			  U.Name as Name,
			  U.ID as ID,
			  L.LastLinkTime
		  FROM
			  ".TABLE_PREFIX.'pms_linkman'." L INNER JOIN 
			  ".DBTABLE_USER." U 
			  ON(L.LinkmanID=U.ID)
		  WHERE
			  L.UID=".USER_ID."
		  ORDER BY
			  LastLinkTime DESC";
		$query = $this->DatabaseHandler->Query($sql);
		while($row=$query->GetRow())
		{
			$row['LastLinkTime']=my_date_format($row['LastLinkTime']);
			$LinkmanList[]=$row;
		}
		$CurrentURL="index.php?mod=pm";
		$MYMENU=$this->CommonHandler->GetUcpMenu();
		$ucp_tabs = $this->_doNav();

		include($this->TemplateHandler->Template('list_pm_linkman'));

	}

	function DeleteLinkman()
	{
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'pms_linkman');
		$ids=$this->DatabaseHandler->BuildIn($this->IDS,"LinkmanID");
		$this->DatabaseHandler->Delete('',$ids." AND UID=".USER_ID);
		$this->Messager("已经成功删除",referer());
	}


	
	function View()
	{	
		
		$folder=trim($this->Get['folder']); 	
		if(!($folder_name=$this->FolderList[$folder]))$this->Messager("没有指定文件夹");
		$people=$folder=='inbox'?"来自":"发送到";

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
				$row['users']=$folder!="inbox"?$row['msgto']:$row['msgfrom'];
				
				$row['user_id']=$folder!="inbox"?$row['msgtoid']:$row['msgfromid'];
				$pm_info=$row;
				
				continue;
			}
			if($row['id']>$this->ID)$next_id=$row['id'];
		}
		
		if($pm_info==false)
		{
			$this->Messager("短信不存在或您无查看他人短信");
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
		
		extract($pm_info,EXTR_SKIP);		
		$message = $pm_info['message'];
		$nickname = $pm_info['msgnickname'] ? $pm_info['msgnickname'] : $pm_info['msgfrom'];
		
								$send_time=my_date_format($pm_info['dateline']);
		$left_menu=$this->LeftMenu();		
		$this->Title=$subject;		
		$tpl = $folder =='outbox'?'pm_write':"pm_view";
		
		$action="index.php?mod=pm&code=dosend";
		$to_user=$user;
		$mod_link=array('name'=>"站内短信",'url'=>"?mod=pm");
		$folder_link=array('name'=>$folder_name,'url'=>"?mod=pm");
		
		include $this->TemplateHandler->Template('pm_view');
		
	}
	
		function MarkUnread()
	{
		$pmids=$this->DatabaseHandler->BuildIn($this->IDS,'');
		if($pmids==false)$this->Messager("标记未读失败");
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'pms');
		$this->DatabaseHandler->Update(array("new"=>"1"),"pmid IN($pmids) AND msgtoid=".MEMBER_ID);
		$this->Messager("标记未读成功，现在将转入消息列表。","index.php?mod=pm");
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


	
	function LeftMenu()
	{
		$folder=$this->Get['folder']?$this->Get['folder']:'inbox';
		
		
		$sql_list['outbox_count']="SELECT COUNT(*) `count` FROM ".TABLE_PREFIX.'pms'." WHERE msgfromid=".MEMBER_ID." AND folder='outbox'";
		$sql_list['inbox_count']="SELECT COUNT(*) `count` FROM ".TABLE_PREFIX.'pms'." WHERE msgtoid=".MEMBER_ID." AND folder='inbox' AND delstatus!='2'";
		$sql_list['inbox_unread_count']="SELECT COUNT(*) `count` FROM ".TABLE_PREFIX.'pms'." WHERE msgtoid=".MEMBER_ID." AND folder='inbox' AND delstatus!='2' AND new>0";
foreach ($sql_list as $name => $sql)
		{
			$query = $this->DatabaseHandler->Query($sql);
			$row=$query->getRow();
			$this->Stat[$name]=$row['count'];
			
		}
		

		$left_menu_list=
		array
		(
			"短信操作"=>
			array
			(
				'send'=>array('name'=>"发送新消息",'link'=>"?mod=pm&code=send&folder=send",'icon'=>'write'),
			),
			"信箱类型"=>
			array
			(
				'inbox'=>array('name'=>"收件箱",'link'=>"?mod=pm&code=list&folder=inbox",'icon'=>'inbox','stat'=>" [<a  href='index.php?mod=pm&code=list&folder=inbox&filter=newpm' title='未读'>未读 <span id='pm_inbox_unread'>{$this->Stat['inbox_unread_count']}</span></a>] [总 <span id='pm_inbox'>{$this->Stat['inbox_count']}</span>]"),
				'outbox'=>array('name'=>"草稿箱",'link'=>"?mod=pm&code=list&folder=outbox",'icon'=>'send','stat'=>" [{$this->Stat['outbox_count']}]"),
				'track'=>array('name'=>"跟踪已发送",'link'=>"?mod=pm&code=list&folder=track",'icon'=>'send')
			)
		);
	
		ob_start();
		
		include $this->TemplateHandler->Template('pm_left_menu');
		Return ob_get_clean();
	}
	
	
	function _stat()
	{
		$sql_list['outbox_count']="SELECT COUNT(*) `count` FROM ".TABLE_PREFIX.'pms'." WHERE msgfromid=".MEMBER_ID." AND folder='outbox'";
		$sql_list['inbox_count']="SELECT COUNT(*) `count` FROM ".TABLE_PREFIX.'pms'." WHERE msgtoid=".MEMBER_ID." AND folder='inbox' AND delstatus!='2'";
		$sql_list['inbox_unread_count']="SELECT COUNT(*) `count` FROM ".TABLE_PREFIX.'pms'." WHERE msgtoid=".MEMBER_ID." AND folder='inbox' AND delstatus!='2' AND new>0";
		
		
		foreach ($sql_list as $name =>$sql)
		{
			$query = $this->DatabaseHandler->Query($sql);
			$row=$query->getRow();
			$this->Stat[$name]=$row['count'];
			
		}
		$this->Stat['total']=$outbox_count+$inbox_count;
		if ($this->Stat['inbox_unread_count']!=$this->MemberHandler->MemberFields['newpm'])
		{
			$sql="UPDATE ".TABLE_PREFIX.'members'." SET newpm={$this->Stat['inbox_unread_count']} WHERE uid=".MEMBER_ID;
			$this->DatabaseHandler->Query($sql,"mysql_unbuffered_query");
		}
	}
}

?>