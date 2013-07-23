<?php
/**
 * 文件名：topic.mod.php
 * 版本号：1.0
 * 最后修改时间：2009年9月28日 14时10分42秒
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 微博模块
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	var $TopicLogic;	

	function ModuleObject($config)
	{
		$this->MasterObject($config);

		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);
		
		Load::logic('longtext');
		$this->LongtextLogic = new LongtextLogic($this);
		
		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();
		switch($this->Code)
		{	
			case 'delete':
				$this->Delete();
				break;			
			case 'modify':
				$this->Modify();
				break;
		  case 'domodify':
				$this->DoModify();
				break;
			case 'modifylist':
				$this->ModifyList();
				break;
			case 'del_img':
				$this->DeleteImg();
				break;
			case 'del_video':
				$this->DeleteVideo();
				break;
			case 'del_music':
				$this->DeleteMusic();
				break;
			default:
				$this->Main();
				break;
		}
		$body = ob_get_clean();
		
		$this->ShowBody($body);
		
	}

	function Main()
	{
		$per_page_num = min(500,max((int) $_GET['per_page_num'],(int) $_GET['pn'],20));
		if($_GET['pn']) $pn = '&pn='.$_GET['pn'];
		$where_list = array();
		$query_link = 'admin.php?mod=topic'.$pn;
		
		$type = $this->Get['type'];
		
				$keyword = trim($this->Get['keyword']);
		if ($keyword) {
			$_GET['highlight'] = $keyword;

			$where_list['keyword'] = build_like_query('content,content2',$keyword);
			$query_link .= "&keyword=".urlencode($keyword);
		
		}
				$nickname = trim($this->Get['nickname']);
		if ($nickname) {
			
			$sql = "select `username`,`nickname` from `".TABLE_PREFIX."members` where `nickname`='{$nickname}' limit 0,1";
			$query = $this->DatabaseHandler->Query($sql);
			$members=$query->GetRow();
		
			$where_list['username'] = "`username`='{$members['username']}'";
			
			$query_link .= "&nickname=".urlencode($members['nickname']);
		}
				
		if($type) {
			
			$where = '';
			if($type == 'pic'){
				$where = " where imageid > 0";
			}elseif($type == 'video'){
				$where = " where videoid > 0";
			}elseif($type == 'music'){
				$where = " where musicid > 0";
			}
			
			$query_link .= "&type={$type}";
			
		} else {	
			
			$where = (empty($where_list)) ? null : ' WHERE '.implode(' AND ',$where_list).' ';
			
		}
		
		$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."topic` {$where} ";
		
		$query = $this->DatabaseHandler->Query($sql);
		
		extract($query->GetRow());
		
		$page_arr = page($total_record,$per_page_num,$query_link,array('return'=>'array',),'20 50 100 200,500');		

		$topic_list = $this->TopicLogic->Get(" {$where} order by `dateline` desc {$page_arr['limit']} ");
		
		include($this->TemplateHandler->Template('admin/topic'));
	}
	


	function ModifyList()
	{	 
		 $title = "编辑微博";
		 $action = "admin.php?mod=topic&code=domodify";
		 $tid = (int) $this->Get['tid'];
		 
		 if(!empty($tid)) {

									
			$topiclist = $this->TopicLogic->Get($tid);
			
			        	$row = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."topic where `tid`='$tid'");
       		$topiclist['content'] = ($row['content'] . $row['content2']);
		
	
		 } else {
		 		$this->Messager(NULL,'admin.php?mod=topic',0);
		 }
			
		 if($topiclist==false) 
		 {
		 		$this->Messager("您要编辑的微博信息已经不存在!");
		 }
		
		
        		$topiclist['content'] = preg_replace('~<U ([0-9a-zA-Z]+)>(.+?)</U>~','',$topiclist['content']);

	    		$topiclist['content'] = strip_tags($topiclist['content']);	
		
				if('both'==$topiclist['type'] || 'forward'==$topiclist['type'])
		{
			$topiclist['content'] = $this->TopicLogic->GetForwardContent($topiclist['content']);
		}
		
		
		$image_list = array();
		if($topiclist['imageid']){
			$this->DatabaseHandler->SetTable(TABLE_PREFIX.'topic_image');
			$image_id_arr = explode(",",$topiclist['imageid']);
			foreach ($image_id_arr as $value) {
				$img = $this-> DatabaseHandler->Select($value);
	 		 	$image_list[$img['id']]['id'] = $img['id'];
				$image_list[$img['id']]['img_path'] = topic_image($img['id']);
			 }
		 }
		 
		 if($topiclist['videoid'])
		 {
		 		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'topic_video');
			  $video = $this-> DatabaseHandler->Select($topiclist['videoid']);
			  
			  $videoid 	 = $video['id'];
			  $videohost = $video['video_hosts'];
			  $videolink = $video['video_link'];
			  $videoimg  = $video['video_img'];
		 }
		 
		 if($topiclist['musicid']){
		 		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'topic_music');
			  $topic_music = $this-> DatabaseHandler->Select($topiclist['musicid']);
			  
			  $musicid_id = $topic_music['id'];
			  $ContentMusicid =  $topic_music['music_url'];
		 }
		
		include $this->TemplateHandler->Template('admin/topic_info');
	}
	
	function DoModify()
	{	 
		extract($this->Get);
		extract($this->Post);
		 
		$sql = "select * from `".TABLE_PREFIX."topic` where `tid` = '{$tid}' limit 0,1";
		$query = $this->DatabaseHandler->Query($sql);
		$topiclist=$query->GetRow();
		
		if($topiclist['content2'])
		{
			$sql = "update `" . TABLE_PREFIX . "topic` set `content2`='' where `tid`='{$tid}'";
        	$this->DatabaseHandler->Query($sql);
		}
		
		preg_match_all('~(?:https?\:\/\/)(?:[A-Za-z0-9_\-]+\.)+[A-Za-z0-9]{2,4}(?:\/[\w\d\/=\?%\-\&_\~`@\[\]\:\+\#]*(?:[^<>\'\"\n\r\t\s])*)?~',$topiclist['content'],$match);
		$is_post_url = implode(glue,$match[0]);  
		
		        preg_match_all('~(.+?)</U>~', $topiclist['content'], $URL);
        $url_tag = implode($URL[0]);
        
		$content		=  strip_tags($this->Post['content']);
		$totid 			=  $topiclist['totid'];
		$imageid 		=  $topiclist['imageid'];
		$type 			=  $topiclist['type'];
		$uid  			=  $topiclist['uid'];
		$username 		=  $topiclist['username'];
		$tid  			=  $topiclist['tid'];

		$return = $this->TopicLogic->Modify($tid,$content,$imageid);
    
		 if(!is_array($return)) {
		 		$this->Messager("【编辑失败】{$return}");	
		 }
		 else {	

		 		$this->Messager("编辑成功",'admin.php?mod=topic');
	 	 }
			 	 
	}
	
	function Delete()
	{
		$ids = (array) ($this->Post['ids'] ? $this->Post['ids'] : $this->Get['ids']);
		
		if(!$ids) {
			$this->Messager("请指定要删除的对象");
		}
		
		$return = $this->TopicLogic->Delete($ids);
		
		$this->Messager($return ? $return : "操作成功");
	}
	
	function DeleteImg()
	{			
		$tid = (int) $this->Get['tid'];
		$ids =  ($this->Post['ids'] ? $this->Post['ids'] : $this->Get['ids']);
		if(!$ids) {
			$this->Messager("请指定要删除的对象");
		}
		
		$sql = "delete from `".TABLE_PREFIX."topic_image` where `id`='{$ids}'";
		$this->DatabaseHandler->Query($sql);
		
		Load::lib('io');
		IoHandler::DeleteFile(topic_image($ids,'small'));
		IoHandler::DeleteFile(topic_image($ids,'original'));
		
		$imageid = $this->DatabaseHandler->ResultFirst("select imageid from ".TABLE_PREFIX."topic where tid = '$tid'");

		if(!$imageid) {
		    $this->Messager("请指定要删除的对象");
		}
		$image_id_arr = explode(",",$imageid);
		foreach ($image_id_arr as $key=>$value) {
			if($value == $ids){
				unset($image_id_arr[$key]);
			}
		}
		$new_imageid = implode(",",$image_id_arr);
		$updata = "update ".TABLE_PREFIX."topic set `imageid`='$new_imageid' where `tid`= '$tid'";	
		$result = $this->DatabaseHandler->Query($updata);

		$this->Messager($return ? $return : "操作成功");
	}
	
	function DeleteVideo()
	{
        	$ids =  ($this->Post['ids'] ? $this->Post['ids'] : $this->Get['ids']);
    		if(!$ids) {
    			$this->Messager("请指定要删除的对象");
    		}
		
			$sql = "select `id`,`tid`,`video_img` from `".TABLE_PREFIX."topic_video` where `id`='".$ids."' ";
			$query = $this->DatabaseHandler->Query($sql);
			$topic_video=$query->GetRow();
			
			
			$sql = "delete from `".TABLE_PREFIX."topic_video` where `id`='{$topic_video['id']}'";
			$this->DatabaseHandler->Query($sql);		
	
			Load::lib('io');
			IoHandler::DeleteFile($topic_video['video_img']);
			
			$updata = "update `".TABLE_PREFIX."topic` set `videoid`='0' where `tid`=".$topic_video['tid'];	
		  $result = $this->DatabaseHandler->Query($updata);
		
			$this->Messager($return ? $return : "操作成功");
	}
	
	function DeleteMusic()
	{		
			$ids =  ($this->Post['ids'] ? $this->Post['ids'] : $this->Get['ids']);
			$sql = "delete from `".TABLE_PREFIX."topic_music` where `tid`='{$ids}'";
			$this->DatabaseHandler->Query($sql);	
			
			$updata = "update `".TABLE_PREFIX."topic` set `musicid`='0' where `musicid`=".$ids;	
			$result = $this->DatabaseHandler->Query($updata);
		
			$this->Messager($return ? $return : "操作成功");
	}
		
}

?>
