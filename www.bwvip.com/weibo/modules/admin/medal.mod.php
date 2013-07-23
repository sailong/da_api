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
		
		Load::lib('form');
		$this->FormHandler = new FormHandler();
		
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
			case 'add':
				$this->Add();
				break;
			case 'modify':
				$this->Modify();
				break;
		  case 'domodify':
				$this->DoModify();
				break;
			case 'add_medal_user':
				$this->Add_Mdeal_User();
				break;
			case 'view':
				$this->DoView();
				break;
			case 'delmedaluser':
				$this->DelMdealUser();
				break;
			case 'isopen':
				$this->IsOpen();
				break;
			case 'numbercount':
				$this->NumberCount();
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

		$sql = "select * from `".TABLE_PREFIX."medal`";
	 	$query = $this->DatabaseHandler->Query($sql);
		$medal_list=array();
	  	$_select = array();
		while($row=$query->GetRow())
		{	
			$medal_list[]=$row;
		}
		
		include $this->TemplateHandler->Template('admin/medal');
	}
	
	
		function Add()
	{
		
		if(empty($this->Post['postFlag']))
		{
			$TITLE_LIST = "添加";
			$action = 'admin.php?mod=medal&code=add';
			
			if (empty($this->Post['type'])) {
				$this->Messager("请选择类别",-1);
			}
			
			$chactype = $this->Post['type'];
			
			switch ($chactype) {
				case 'topic':
				$typename = "连续发原创微博";
				break;
				
				case 'reply':
				$typename = "连续评论微博";
				break;
				
				case 'tag':
				$typename = "发布指定话题";
				break;
				
				case 'invite':
				$typename = "邀请好友";
				break;
				
				default:
					$typename = "";
				break;
			}
			
			
			include $this->TemplateHandler->Template('admin/medal_info');	
		}
		else
		{
			
				$error_msg = '';
				$field = 'medal';
				$datetime = time();
				if(empty($_FILES) || !$_FILES[$field]['name'])
				{
						$this->Messager("请设置图片",-1);
				}
				
				
				$medal_name 	= $this->Post['medal_name'];
				$medal_depict = $this->Post['medal_depict'];
				
			  
			   $checkvalue = array(
			  											'type'				=>	$this->Post['type'],
			  											'day'				=>	$this->Post['day'],
	  													'endday'			=>	$this->Post['endday'],
	  													'tagname'			=>	$this->Post['tagname'],
			  											'invite'			=>  $this->Post['invite'],
			  										);
			 
				$checkvalue = serialize($checkvalue);
				
				$sql = "insert into `".TABLE_PREFIX."medal`(`medal_name`,`medal_depict`,`conditions`,`dateline`) values ('{$medal_name}','{$medal_depict}','{$checkvalue}','{$datetime}')";
				$this->DatabaseHandler->Query($sql);
				$image_id = $this->DatabaseHandler->Insert_ID();
				
				Load::lib('io');
				$IoHandler = new IoHandler();

				$image_path = RELATIVE_ROOT_PATH . 'images/' . $field . '/'.$datetime.'/';
				$image_name = $image_id . "_o.jpg";
				$image_file = $image_path . $image_name;
				$image_file_small = $image_path.$image_id . "_s.jpg";
				if (!is_dir($image_path)) {
					$IoHandler->MakeDir($image_path);
					
				}

				Load::lib('upload');
				$UploadHandler = new UploadHandler($_FILES,$image_path,$field,true);
				$UploadHandler->setMaxSize(2048);
				$UploadHandler->setNewName($image_name);
				$result=$UploadHandler->doUpload();

				if($result) {
					$result = is_image($image_file);
				}


				list($image_width,$image_height,$image_type,$image_attr) = getimagesize($image_file);

				$result = makethumb(
					$image_file,
					$image_file_small,
					min(60,$image_width),
					min(60,$image_height),
					60,
					60
				);
				if (!$result && !is_file($image_file_small)) {
					@copy($image_file,$image_file_small);
				}
			
				$image_file = addslashes($image_file);				$image_file_small = addslashes($image_file_small);					
								$img = grayJpeg($image_file_small);
				imagejpeg($img,$image_file_small,100);
				imagedestroy($img);
					
				$sql = "update `".TABLE_PREFIX."medal` set `medal_img`='{$image_file}',`medal_img2`='{$image_file_small}' where `id`='{$image_id}'";
				$this->DatabaseHandler->Query($sql);

				$this->Messager("添加成功",'admin.php?mod=medal');
			}
			
	}
	
	
	function Modify()
	{	 
		 $TITLE_LIST = "编辑";
		 $action = "admin.php?mod=medal&code=domodify";
		 $cheack_type = $this->Post['type'];
		 
		 $datetime = time();
		
		 for($i=1; $i <= 30; $i++) 
		 { 
		 		$option .= "<option value='".$i."'>$i</option>" ; 
		 }
 
		
		 $ids = max(0, (int) $this->Get['ids']);
		 if(!$ids) $this->Messager("请指定一个ID",null);
		
		 $sql="SELECT * FROM ".TABLE_PREFIX.'medal'." WHERE id=".$ids;
		 $query = $this->DatabaseHandler->Query($sql);
		 $medal_info=$query->GetRow();
		
		 $chackvalue = unserialize($medal_info['conditions']);
		 $chactype = $chackvalue['type'];
		 if($medal_info==false) 
		 {
		 		$this->Messager("您要编辑的信息已经不存在!");
		 }


		 include $this->TemplateHandler->Template('admin/medal_info');
		 
	}
	
		function DoModify()
	{
		
		$sql="SELECT * FROM ".TABLE_PREFIX.'medal'." WHERE id=".$this->Post['medal_id'];
	  $query = $this->DatabaseHandler->Query($sql);
	  $medal_info=$query->GetRow();
				 
		$error_msg = '';
		$field = 'medal';
	
		$datetime = time();
		$medal_name 	= $this->Post['medal_name'];
		$medal_depict = $this->Post['medal_depict'];
		
	  
	  $checkvalue = array(
	  											'type'				=>	$this->Post['type'],
	  											'day'					=>	$this->Post['day'],
	  											'endday'			=>	$this->Post['endday'],
	  											'tagname'			=>	$this->Post['tagname'],
	  											'invite'			=>  $this->Post['invite'],
	  										);
		
		$checkvalue = serialize($checkvalue);
		
		$medal_name 	= $this->Post['medal_name'];
		$medal_depict = $this->Post['medal_depict'];

	
		if(empty($_FILES) || !$_FILES[$field]['name'])
		{
				 $image_file = $medal_info['medal_img'];
				 $image_file_small = $medal_info['medal_img2'];
		} 
		else
		{
				Load::lib('io');
				$IoHandler = new IoHandler();
				
								IoHandler::DeleteFile($medal_info['medal_img']);
        IoHandler::DeleteFile($medal_info['medal_img2']);
        
				$image_path = RELATIVE_ROOT_PATH . 'images/' . $field . '/'.$datetime.'/';
				
				$image_name = $medal_info['id'] . "_o.jpg";
				$image_file = $image_path . $image_name;
				$image_file_small = $image_path.$medal_info['id'] . "_s.jpg";
				
				if (!is_dir($image_path)) {
					$IoHandler->MakeDir($image_path);
				}

				Load::lib('upload');
				$UploadHandler = new UploadHandler($_FILES,$image_path,$field,true);
				$UploadHandler->setMaxSize(2048);
				$UploadHandler->setNewName($image_name);
				$result=$UploadHandler->doUpload();

				if($result) {
					$result = is_image($image_file);
				}


					list($image_width,$image_height,$image_type,$image_attr) = getimagesize($image_file);

					$result = makethumb(
						$image_file,
						$image_file_small,
						min(60,$image_width),
						min(60,$image_height),
						60,
						60
					);
					if (!$result && !is_file($image_file_small)) {
						@copy($image_file,$image_file_small);
					}
					
					$image_file = addslashes($image_file);
					$image_file_small = addslashes($image_file_small);
					
										$img = grayJpeg($image_file_small);					imagejpeg($img,$image_file_small,100);					imagedestroy($img);
	
		}			
	
		$sql = "update `".TABLE_PREFIX."medal` set  `medal_img`='{$image_file}' ,`medal_img2` = '{$image_file_small}', `medal_name`='{$medal_name}' ,`medal_depict` = '{$medal_depict}' , `conditions` = '{$checkvalue}'   where `id`=".$this->Post['medal_id'];	
		$this->DatabaseHandler->Query($sql);
		
		
		if ($error_msg) {
			$this->Messager($error_msg);
		}
		$this->Messager("编辑成功",'admin.php?mod=medal');
		
	}

	
	
	
		function DoView()
	{
		$medalid = max(0, (int) $this->Get['ids']);
		if(!$medalid) $this->Messager("请指定一个ID",null);
		
				$sql = "select `id`,`medal_name` from `".TABLE_PREFIX."medal` where `id` = '{$medalid}'";
		$query = $this->DatabaseHandler->Query($sql);
		$medalname = $query->GetRow();
		
				$sql = "select * from `".TABLE_PREFIX."user_medal` where `medalid` = '{$medalid}'";
		$query = $this->DatabaseHandler->Query($sql);
		$usermedal = array();
		$uids = array();
		while($row = $query->GetRow())
		{
			$usermedal[] = $row;
			$uids[$row['uid']] = $row['uid'];
		}
		
		$sql = "select * from `".TABLE_PREFIX."members` where `uid` in('".implode("','",$uids)."')";
		$query = $this->DatabaseHandler->Query($sql);
		$memberslist = array();
		while($row = $query->GetRow())
		{
			$memberslist[] = $row;
		}
		
				$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."user_medal` where `medalid` = '{$medalid}'";
    	$query = $this->DatabaseHandler->Query($sql);
    	extract($query->GetRow());
    		   	
		if ($medalname['medal_count'] != $total_record) {
			
			    		$sql = "update `".TABLE_PREFIX."medal` set  `medal_count`='{$total_record}'  where `id` = '{$medalid}'";	
    		$this->DatabaseHandler->Query($sql);
		}

		 include $this->TemplateHandler->Template('admin/medal_view');
	}
	
	
	
		function Delete()
	{
		$ids = $this->Post['ids'] ? $this->Post['ids'] : $this->Get['ids'];
		if(!$ids) {
			$this->Messager("请指定要删除的对象");
		}
		
		
		$sql = "select * from `".TABLE_PREFIX."members` where `medal_id` in ($ids) ";
		$query = $this->DatabaseHandler->Query($sql);
		$uids = array();
		while ($row = $query->GetRow()) {
			$uids[] = $row['uid'] ;
		}
		
		if($uids){
    		    		$return = $this->_Update_Medal_Id($uids,$ids);
		}
				$sql="delete from `".TABLE_PREFIX."medal` where `id` = '{$ids}'";
		$this->DatabaseHandler->Query($sql);	
		
				$sql = "delete from `".TABLE_PREFIX."user_medal` where `medalid` = '{$ids}'";
		$this->DatabaseHandler->Query($sql);
		
	
				$return = $this->_MedalCount($ids);
		
		$this->Messager($return ? $return : "操作成功");
		
	}
	
		function DelMdealUser()
	{
		$uids = (array) ($this->Post['ids'] ? $this->Post['ids'] : $this->Get['ids']);
		$medalid = $this->Post['medalid'];
		
		
		if(empty($uids)) {
			$this->Messager("请指定要删除的对象");
		}

				$sql = "delete from `".TABLE_PREFIX."user_medal` where `uid` in(".implode(",",$uids).") and `medalid` = {$medalid}";	
		$this->DatabaseHandler->Query($sql);

				$return = $this->_Update_Medal_Id($uids,$medalid);

				$return = $this->_MedalCount($medalid);
		
		$this->Messager("删除成功",'admin.php?mod=medal&code=view&ids='.$medalid);
	}
	
		function Add_Mdeal_User()
	{
		 $nickname = $this->Post['nickname'];
		 $medal_id = (array) $this->Post['medal_id'];
		 $dateline = time();
		 if(empty($nickname))
		 {
		 	 $this->Messager("用户昵称不能为空！");
		 }
		 if(empty($medal_id))
		 {
		 	$this->Messager("勋章类别不能为空");
		 }
 		 
 		
		 $sql = "select `uid`,`medal_id`,`nickname`,`face` from `".TABLE_PREFIX."members` where `nickname` = '{$nickname}'";
		 $query = $this->DatabaseHandler->Query($sql);
		 $members=$query->GetRow();

		 if(empty($members)){
	 	 	 $this->Messager("输入的昵称 <font color='#ff0000'> {$nickname} </font>不存在"); 
	 	 }	
	
		
		    	for ($i = 0; $i < count($medal_id); $i++) {
    		
        	    		$sql = " select * from `".TABLE_PREFIX."user_medal` where `medalid` = '{$medal_id[$i]}' and `uid` = '{$members['uid']}'";
    		$query = $this->DatabaseHandler->Query($sql);
    		$row = $query->GetRow();
    		
			if(empty($row))
			{
        		$sql = "insert into `".TABLE_PREFIX."user_medal` (`uid`,`nickname`,`medalid`,`dateline`) values ('{$members['uid']}','{$members['nickname']}','{$medal_id[$i]}','{$dateline}')";
    			$query = $this->DatabaseHandler->Query($sql);
    			
    			    			$return = $this->_MedalCount($medal_id[$i]);
			}
    	}

 		 		$sql = "select * from `".TABLE_PREFIX."user_medal` where `uid` = '{$members['uid']}'";
		$query = $this->DatabaseHandler->Query($sql);
		$user_medal = array();
		while ($row = $query->GetRow()) {
			$user_medal[] = $row['medalid'];
		}
		
		$new_medalid = implode(",",$user_medal);
		
		$sql = "update `".TABLE_PREFIX."members` set  `medal_id`='{$new_medalid}'  where `uid` = '{$members['uid']}'";	
		$this->DatabaseHandler->Query($sql);
		
		$this->Messager("编辑成功",'admin.php?mod=medal');
			
	}
	
	
		function _MedalCount($medalid=0)
	{
		if($medalid){	
    		    		$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."user_medal` where `medalid` = '{$medalid}'";
    		$query = $this->DatabaseHandler->Query($sql);
    		extract($query->GetRow());
    		
    		    		$sql = "update `".TABLE_PREFIX."medal` set  `medal_count`='{$total_record}'  where `id` = '{$medalid}'";	
    		$this->DatabaseHandler->Query($sql);
		}else{
		
			$this->Messager("勋章已删除",'admin.php?mod=medal');
		}	
	}
	
		function _Update_Medal_Id($uids,$medalid) {
		
		$medalid = (int) $medalid;
		
		$sql = "select `uid`,`medal_id` from `".TABLE_PREFIX."members` where `uid` in (".implode(",",$uids).")";
		$query = $this->DatabaseHandler->Query($sql);
		$members = array();
		while($row = $query->GetRow())
		{
			$members[] = $row;
		}

		foreach($members as $user_medal)
		{  	 
		 	$new_medalid = str_replace($medalid,'',$user_medal['medal_id']);
			
			$new_medalid = trim($new_medalid,',');
			$array	=	explode(',',$new_medalid);
			
			foreach($array   as   $key=>$val){
    		    if(empty($array[$key]))   
    		    unset($array[$key]);   
		  	}
  
 			$new_medalid = implode(",",$array);
		
			$sql = "update `".TABLE_PREFIX."members` set  `medal_id`='{$new_medalid}'  where `uid` = '{$user_medal['uid']}'";	
			$this->DatabaseHandler->Query($sql);
		}
 			
	}
	
	function IsOpen()
	{
		$medalid = (int) $this->Get['ids'];	
		
		$sql = "select * from `".TABLE_PREFIX."medal` where `id` = {$medalid}";
		$query = $this->DatabaseHandler->Query($sql);
		$row = $query->GetRow();
		
		$is_open = $row['is_open'] ? 0 : 1;
		
		$sql = "update `".TABLE_PREFIX."medal` set  `is_open`='{$is_open}'  where `id` = '{$medalid}'";	
		$this->DatabaseHandler->Query($sql);
		
		$this->Messager("编辑成功",'admin.php?mod=medal');
		
	}
	
	
	
	
	

		
}

?>
