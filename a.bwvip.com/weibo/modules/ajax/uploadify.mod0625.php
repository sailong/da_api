<?php
/**
 * 文件名：uploadify.mod.php
 * 版本号：1.0
 * 最后修改时间：2011年6月22日
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 测试模块
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	var $ImageLogic;
	
	var $type;
	

	function ModuleObject($config)
	{
		$this->MasterObject($config);
		
		Load::logic('image');
		$this->ImageLogic = new ImageLogic();

		$this->Execute();
	}

	function Execute()
	{
		switch($this->Code)
		{
			case 'image':
				$this->Image();
				break;
			case 'delete_image':
				$this->DeleteImage();
				break;
				
			case 'html':
				$this->Html();
				break;

			default:
				$this->Main();
				break;
		}
	}
	
	function Main()
	{
		response_text('正在建设中……');
		
	}
	
	function Html()
	{
		$tid = max(0, (int) ($this->Post['tid'] ? $this->Post['tid'] : $this->Get['tid']));
		$image_uploadify_topic = array();
		if($tid > 0)
		{
			Load::logic('topic');
			$TopicLogic = new TopicLogic($this);
			
			$image_uploadify_topic = $TopicLogic->Get($tid);
		}
				
		
		$from = ($this->Request['image_uploadify_from'] ? $this->Request['image_uploadify_from'] : $this->Request['from']);
		$image_uploadify_from = '';
		if('topic_publish' == $from)
		{
			$image_uploadify_from = $from;
		}
		
		
		$only_js = ($this->Request['image_uploadify_only_js'] ? $this->Request['image_uploadify_only_js'] : $this->Request['only_js']);
		$image_uploadify_only_js = 0;
		if($only_js)
		{
			$image_uploadify_only_js = 1;
		}
		
		
		$topic_uid = max(0, (int) ($this->Request['image_uploadify_topic_uid'] ? $this->Request['image_uploadify_topic_uid'] : $this->Request['topic_uid']));
		$image_uploadify_topic_uid = 0;
		if($topic_uid)
		{
			$image_uploadify_topic_uid = $image_uploadify_topic['uid'];
		}
		
		
		$image_small_size = max(0, (int) ($this->Request['image_uploadify_image_small_size'] ? $this->Request['image_uploadify_image_small_size'] : $this->Request['image_small_size']));
		$image_uploadify_image_small_size = 45;
		if($image_small_size)
		{
			$image_uploadify_image_small_size = $image_small_size;
		}
		
		
		$image_uploadify_new = ($this->Request['image_uploadify_new'] ? $this->Request['image_uploadify_new'] : $this->Request['new']);
		
		$image_uploadify_modify = ($this->Request['image_uploadify_modify'] ? $this->Request['image_uploadify_modify'] : $this->Request['modify']);
		
		$image_uploadify_type = ($this->Request['image_uploadify_type'] ? $this->Request['image_uploadify_type'] : $this->Request['type']);
		
		$content_textarea_id = ($this->Request['content_textarea_id'] ? $this->Request['content_textarea_id'] : $this->Rqeust['content_id']);
		
		if(isset($this->Request['content_textarea_empty_val']))
		{
			$content_textarea_empty_val = $this->Request['content_textarea_empty_val'];
		}
		
		
		
		include($this->TemplateHandler->Template('image_uploadify.inc'));
	}
	
	function Image()
	{
				$this->_init_auth();
		
				$field = 'topic';
		if (empty($_FILES) || !$_FILES[$field]['name']) 
		{
			$this->_image_error('FILES is empty');
		}
		$_FILES[$field]['name'] = array_iconv('UTF-8', $this->Config['charset'], $_FILES[$field]['name']);
		
				$uid = MEMBER_ID;
		$username = MEMBER_NAME;
		
				$image_id = $this->ImageLogic->add($uid, $username);
		if($image_id < 1)
		{
			$this->_image_error('write database is invalid');
		}
		
		
		$this->ImageLogic->clear_invalid();
		
		
				Load::lib('io');
		$IoHandler = new IoHandler();
		$image_path = RELATIVE_ROOT_PATH . 'images/' . $field . '/' . face_path($image_id);
		$image_name = $image_id . "_o.jpg";
		$image_file = $image_path . $image_name;
		$image_file_small = $image_path.$image_id . "_s.jpg";
		if (!is_dir($image_path)) 
		{
			$IoHandler->MakeDir($image_path);
		}
		
				Load::lib('upload');
		$UploadHandler = new UploadHandler($_FILES,$image_path,$field,true);		
		$UploadHandler->setMaxSize(2048);
		$UploadHandler->setNewName($image_name);
		$ret = $UploadHandler->doUpload();
		if($ret) 
		{
			$ret = is_image($image_file);
		}
		
				if(!$ret)
		{
			$IoHandler->DeleteFile($image_file);
			$this->DatabaseHandler->Query("delete from ".TABLE_PREFIX."topic_image where `id`='$image_id'");
			$rets = $UploadHandler->getError();
			$ret = ($rets ? implode(" ", (array) $rets) : 'image file is invalid');
			
			$this->_image_error($ret);
		}
		
				list($image_width,$image_height,$image_type,$image_attr) = getimagesize($image_file);
		$thumbwidth = min($this->Config['thumbwidth'],$image_width);
		$thumbheight = min($this->Config['thumbheight'],$image_width);
		
	
				$maxw = $this->Config['maxthumbwidth'];
		$maxh = $this->Config['maxthumbheight'];
		$result = makethumb($image_file, $image_file_small, $thumbwidth, $thumbheight, $maxw, $maxh);
				
		
				if($image_width != $image_height)
		{
			$iw = $image_width;
			$ih = $image_height;
			if($maxw > 300 && $maxh > 300 && ($iw > $maxw || $ih > $maxh))
			{
								list($iw, $ih) = getimagesize($image_file);
			}
			
			$src_x = $src_y = 0;
			$src_w = $src_h = min($iw, $ih);
			if($iw > $ih)
			{
				$src_x = round(($iw - $ih) / 2);
			}
			else
			{
				$src_y = round(($ih - $iw) / 2);
			}
			$result = makethumb($image_file, $image_file_small, $thumbwidth, $thumbheight, 0, 0, $src_x, $src_y, $src_w, $src_h);
		}
		
		if (!$result && !is_file($image_file_small)) 
		{
			@copy($image_file,$image_file_small);
		}
		

				if($this->Config['watermark_enable']) 
		{
			$arr = @getimagesize($image_file);
			if($arr && 'image/gif' != $arr['mime'] && 'image/png' != $arr['mime'])
			{
				$this->_watermark($image_file,$this->Config['site_url'] . "/" . MEMBER_NAME);
			}
		}

				$site_url = '';
		if($this->Config['ftp_on'])
		{
			$site_url = ConfigHandler::get('ftp','attachurl');
			
			$ftp_result = ftpcmd('upload',$image_file);
			if($ftp_result > 0)
			{
				ftpcmd('upload',$image_file_small);
				
				$IoHandler->DeleteFile($image_file);
				$IoHandler->DeleteFile($image_file_small);
				
				$image_file_small = $site_url . '/' . $image_file_small; 
			}
		}
		
				$image_size = filesize($image_file);
		$name = addslashes($_FILES[$field]['name']);		
		
		$p = array(
			'id' => $image_id,
		
			'site_url' => $site_url,
			'photo' => $image_file,
			'name' => $name,
			'filesize' => $image_size,
			'width' => $image_width,
			'height' => $image_height,
		);
		$this->ImageLogic->modify($p);
		
		
				$retval = array(
			'id' => $image_id,
			'src' => $image_file_small,
		);
		$this->_image_result('ok',$retval);
	}
	
		function DeleteImage()
	{
		$this->initMemberHandler();
		if(MEMBER_ID < 1)
		{
			json_error('请先登录或者注册一个帐号');
		}
		
		$id = max(0, (int) ($this->Post['id'] ? $this->Post['id'] : $this->Get['id']));
		$topic_image = $this->ImageLogic->get_info($id);
		if(!$topic_image)
		{
			json_error('请指定一个正确的图片ID');
		}
		
		if($topic_image['uid'] != MEMBER_ID && 'admin' != MEMBER_ROLE_TYPE)
		{
			json_error('您无权删除该图片');
		}
		
		
		$ret = $this->ImageLogic->delete($id);
		if(!$ret)
		{
			json_error('删除失败');
		}
		
		
		json_result('删除成功');		
	}
	
	function _init_auth()
	{		
		$type = ($this->Post['type'] ? $this->Post['type'] : $this->Get['type']);
		$this->Type = $type;
		
		if('normal' == $type)
		{
			$this->initMemberHandler();
		}
		else 
		{
			$uid = 0;
			$password = '';		
			$members = array();
			
			$cookie_auth = ($this->Post['cookie_auth'] ? $this->Post['cookie_auth'] : $this->Get['cookie_auth']);
					
			list($password,$uid) = ($cookie_auth ? explode("\t", authcode(str_replace(' ', '+', $cookie_auth), 'DECODE')) : array('', 0));
			
			if($uid > 0)
			{
				$members = $this->DatabaseHandler->FetchFirst("select `uid`, `username`, `nickname`, `role_type` from ".TABLE_PREFIX."members where `uid`='$uid'");
			}
			
			if(!$members)
			{
				json_error('auth is invalid');
			}
			else 
			{
				$topic_uid = max(0, (int) ($this->Post['topic_uid'] ? $this->Post['topic_uid'] : $this->Get['topic_uid']));
				if($topic_uid > 0 && $topic_uid != $uid && 'admin'==$members['role_type'])
				{
					$members = $this->DatabaseHandler->FetchFirst("select `uid`, `username`, `nickname`, `role_type` from ".TABLE_PREFIX."members where `uid`='$topic_uid'");
				}
				
				define('MEMBER_ID', $members['uid']);
				define('MEMBER_NAME', $members['username']);
				define('MEMBER_NICKNAME', $members['nickname']);
				define('MEMBER_ROLE_TYPE', $members['role_type']);
			}
		}
	}
	
	
	function _watermark($pic_path,$watermark,$new_pic_path='')
	{
		if(false === is_file($pic_path)) {
			return false;
		}
		if('' == trim($watermark)) {
			 return false;
		}
		$sys_config = ConfigHandler::get();
		if (!$sys_config['watermark_enable']) {
			return false;
		}
		if('' == $new_pic_path) {
			$new_pic_path = $pic_path;
		}

		require_once(ROOT_PATH . 'include/lib/thumb.class.php');
		$_thumb = new ThumbHandler();
		$_thumb->setSrcImg($pic_path);
		$_thumb->setDstImg($new_pic_path);
		$_thumb->setImgCreateQuality(80);
	
		$_thumb->setMaskPosition($sys_config['watermark_position']);
	
		if(is_file($watermark))
		{
			$_thumb->setMaskImgPct(100);
			
			$_thumb->setMaskImg($watermark);
			
		}
		else
		{
						$mask_word = (string) $watermark;
			if (preg_match('~[\x7f-\xff][\x7f-\xff]~',$mask_word)) {
				if(is_file(RELATIVE_ROOT_PATH . 'images/jsg.ttf')) {
					$_thumb->setMaskFont(RELATIVE_ROOT_PATH . 'images/jsg.ttf');
					$mask_word = array_iconv($this->Config['charset'],'utf-8',$mask_word);
				} else {
					$mask_word = $sys_config['site_url'];
				}
			}

			$_thumb->setMaskWord($mask_word);
		}
		
		return $_thumb->createImg(100);
		
	}
	
	function _image_error($msg)
	{
		if('normal' == $this->Type)
		{
						echo "<script type='text/javascript'>window.parent.MessageBox('warning', '{$msg}');</script>";
			exit ;
		}
		else 
		{
			json_error($msg);
		}
	}
	function _image_result($msg, $retval=null)
	{
		if('normal' == $this->Type)
		{
			$image_uploadify_id = ($this->Post['image_uploadify_id'] ? $this->Post['image_uploadify_id'] : $this->Get['image_uploadify_id']);
			
			echo "<script type='text/javascript'>
			window.parent.imageUploadifyComplete{$image_uploadify_id}('{$retval['id']}', '{$retval['src']}');
			window.parent.imageUploadifyAllComplete{$image_uploadify_id}();
			</script>";
			exit ;
		}
		else 
		{
			json_result($msg, $retval);
		}
	}
	
}

?>
