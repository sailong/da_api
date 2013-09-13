<?php
/**
 *    #Case		bwvip
 *    #Page		Push_messageAction.class.php (消息推送)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class sys_messageAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function sys_message()
	{
		/*
		echo urlencode("Golf-Sense 高尔夫辅助训练系统产品介绍");
		echo "<hr>";
		echo urlencode("大正网2013全新推出Golf-Sense升级版，正火热促销中");
		*/
		
		$list=D("sys_message")->sys_message_list();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","消息推送");
    	$this->display('sys_message');
	}

	public function sys_message_add()
	{
		$action_list=select_dict(16,"select");
		$this->assign("action_list",$action_list);

		$this->assign("page_title","添加系统消息");
    	$this->display('sys_message_add_android');
	}

	public function sys_message_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$field_uid=post("field_uid");
			
			//$max=M()->query("select max(message_number) as max_id from tbl_sys_message where message_type='".post("message_type")."'  ");
			//$data["message_number"]=$max[0]['max_id']+1;
			//$data["message_type"]=post("message_type");
			$data["field_uid"]=post("field_uid");
			if(post("uid"))
			{
				$uid=post("uid");
				//$is_push=M()->query("select if_push from pre_common_member_profile where uid='".$uid."' ");
			}
			else
			{
				$uid=0;
			}
			$data["uid"]=$uid;
			$data["message_title"]=post("message_title");

			$n_title=post("message_title");
			$n_content=post("n_content");
			$ext_action=post("ext_action");
			$ext_id=post("ext_id");
			$ext_title=post("ext_title");
			
			
			if(post("ext_action") == '')
			{
				$message_extinfo=array('action'=>"system_msg");	
			}
			else
			{
				$message_extinfo=array('action'=>$ext_action,'id'=>$ext_id,'title'=>$ext_title);
			}
			
			//$msg_content = json_encode(array('n_builder_id'=>0, 'n_title'=>urlencode($n_title), 'n_content'=>urlencode($n_content),'n_extras'=>$message_extinfo));
			$msg_content = json_encode(array('n_title'=>urlencode($n_title), 'n_content'=>urlencode($n_content),'n_extras'=>$message_extinfo));

			$data["message_content"]=$msg_content;
			$data["receiver_type"]=post("receiver_type");
			$data['message_pic']='';
			if($_FILES["message_pic"]['error']==0) {
			    $file_path="/upload/xiaoxi_pic/";
        		$time_name = time();
    			if(!file_exists(WEB_ROOT_PATH.$file_path))
    			{
    				mkdir(WEB_ROOT_PATH.$file_path);
    			}
    			$file_path .=date("Ymd",$time_name)."/";
    			if(!file_exists(WEB_ROOT_PATH.$file_path))
    			{
    				mkdir(WEB_ROOT_PATH.$file_path);
    			}
    			$extname=end(explode(".",$_FILES["message_pic"]["name"]));
    			//$file_name = iconv('utf-8','gb2312',$_FILES["qiutong_photo"]["name"]);
    			$file_path .= $time_name.'.'.$extname;
    			$rs = move_uploaded_file($_FILES["message_pic"]["tmp_name"], WEB_ROOT_PATH.$file_path);//将上传的文件存储到服务器
			    if(!empty($rs)) {
			        $data['message_pic'] = $file_path;
			    }
			}
		
			$data["message_state"]=0;
			$data["message_totalnum"]=0;
			$data["message_sendnum"]=0;
			$data["message_errorcode"]="";
			$data["message_errormsg"]="";
			$data["message_addtime"]=time();
			
			if($_FILES["message_pic"]['error']==0) {
			    $file_path="/upload/xiaoxi_pic/";
        		$time_name = time();
    			if(!file_exists(WEB_ROOT_PATH.$file_path))
    			{
    				mkdir(WEB_ROOT_PATH.$file_path);
    			}
    			$file_path .=date("Ymd",$time_name)."/";
    			if(!file_exists(WEB_ROOT_PATH.$file_path))
    			{
    				mkdir(WEB_ROOT_PATH.$file_path);
    			}
    			$extname=end(explode(".",$_FILES["message_pic"]["name"]));
    			//$file_name = iconv('utf-8','gb2312',$_FILES["qiutong_photo"]["name"]);
    			$file_path .= $time_name.'.'.$extname;
    			$rs = move_uploaded_file($_FILES["message_pic"]["tmp_name"], WEB_ROOT_PATH.$file_path);//将上传的文件存储到服务器
			    if(!empty($rs)) {
			        $data['message_pic'] = $file_path;
			    }
			}
			$list=M("sys_message")->add($data);

			if($list!=false)
			{
				$this->success("添加成功",U('admin/sys_message/sys_message'));
			}
			else
			{				
				$this->error("添加失败",U('admin/sys_message/sys_message'));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}



	public function sys_message_edit()
	{
		if(intval(get("message_id"))>0)
		{
			$data=M("sys_message")->where("message_id=".intval(get("message_id")))->find();
			
			$data['message_content']=json_decode($data['message_content'],true);
			
			$data['message_content']['n_title'] = urldecode($data['message_content']['n_title']);
			$data['message_content']['n_content'] = urldecode($data['message_content']['n_content']);
			
			$this->assign("data",$data);
			
			$action_list=select_dict(16,"select");
			$this->assign("action_list",$action_list);
			
			$this->assign("page_title","修改系统消息");
			$this->display('sys_message_edit_android');
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function sys_message_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data['message_id']=intval(post("message_id"));
			if(post("uid"))
			{
				$data["uid"]=post("uid");
			}
			else
			{
				$data["uid"]=0;
			}
			$data["message_title"]=post("message_title");

			$n_title=post("message_title");
			$n_content=post("n_content");
			$ext_action=post("ext_action");
			$ext_id=post("ext_id");
			$ext_title=post("ext_title");
			
			
			if(post("ext_action") == '')
			{
				$message_extinfo=array('action'=>"system_msg");	
			}
			else
			{
				$message_extinfo=array('action'=>$ext_action,'id'=>$ext_id,'title'=>$ext_title);
			}
			
			$msg_content = json_encode(array('n_title'=>urlencode($n_title), 'n_content'=>urlencode($n_content),'n_extras'=>$message_extinfo));

			$data["message_content"]=$msg_content;
			$data["receiver_type"]=post("receiver_type");
			$data["message_sendtime"]=$msg_content;
			
			if($_FILES["message_pic"]['error']==0) {
			    $file_path="/upload/xiaoxi_pic/";
        		$time_name = time();
    			if(!file_exists(WEB_ROOT_PATH.$file_path))
    			{
    				mkdir(WEB_ROOT_PATH.$file_path);
    			}
    			$file_path .=date("Ymd",$time_name)."/";
    			if(!file_exists(WEB_ROOT_PATH.$file_path))
    			{
    				mkdir(WEB_ROOT_PATH.$file_path);
    			}
    			$extname=end(explode(".",$_FILES["message_pic"]["name"]));
    			//$file_name = iconv('utf-8','gb2312',$_FILES["qiutong_photo"]["name"]);
    			$file_path .= $time_name.'.'.$extname;
				
    			$rs = move_uploaded_file($_FILES["message_pic"]["tmp_name"], WEB_ROOT_PATH.$file_path);//将上传的文件存储到服务器
				
			    if(!empty($rs)) {
			        $data['message_pic'] = $file_path;
			    }
			}

			$data["message_addtime"]=time();
			
			$list=M("sys_message")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/sys_message/sys_message'));
			}
			else
			{
				$this->error("修改失败",U('admin/sys_message/sys_message'));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}
	}

	/* public function sys_message_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{

			$data["message_id"]=post("message_id");
			$data["message_type"]=post("message_type");
			$data["field_uid"]=post("field_uid");
			if(post("uid"))
			{
				$data["uid"]=post("uid");
			}
			else
			{
				$data["uid"]=0;
			}
			
			$data["message_title"]=post("message_title");

			if(post("message_type")=="android")
			{
				$n_title=post("message_title");
				$n_content=post("n_content");
				$ext_action=post("ext_action");
				$ext_id=post("ext_id");
				$ext_title=post("ext_title");
				
				if(post("receiver_type")==3)
				{
					$message_extinfo=array('action'=>"system_msg");	
				}
				else
				{
					$message_extinfo=array('action'=>$ext_action,'id'=>$ext_id,'title'=>urlencode($ext_title));
				}
				$msg_content = json_encode(array('n_builder_id'=>0, 'n_title'=>urlencode($n_title), 'n_content'=>$n_content,'n_extras'=>$message_extinfo));

				$data["message_content"]=$msg_content;
				$data["receiver_type"]=post("receiver_type");
			}
			else
			{
				$data["message_content"]=post("message_content");
				$data["devices_token"]=post("devices_token");
				$data["receiver_type"]=post("receiver_type");
			}
			
			$data['message_pic']=post("message_pic");
			
			$data["message_state"]=post("message_state");
			$data["message_totalnum"]=post("message_totalnum");
			$data["message_sendnum"]=post("message_sendnum");
			$data["message_errorcode"]=post("message_errorcode");
			$data["message_errormsg"]=post("message_errormsg");
			$data["message_addtime"]=time();
			
			$list=M("sys_message")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/sys_message/sys_message'));
			}
			else
			{
				$this->error("修改失败",U('admin/sys_message/sys_message'));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	 }*/

	public function sys_message_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("sys_message")->where("message_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function sys_message_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_sys_message set push_message_state=1 where message_id=".$ids_arr[$i]." ");
			}
			if($res)
			{
				echo "succeed^审核成功";
			}
			else
			{
				echo "error^审核成功";
			}			
			
		}
	}

	public function sys_message_detail()
	{
		if(intval(get("message_id"))>0)
		{
			$data=M("sys_message")->where("message_id=".intval(get("message_id")))->find();
			
			if(!empty($data))
			{
				$data['message_content']=json_decode($data['message_content'],true);
			
				$data['message_content']['n_title'] = urldecode($data['message_content']['n_title']);
				$data['message_content']['n_content'] = urldecode($data['message_content']['n_content']);
				$this->assign("data",$data);

				$this->assign("page_title",$data["push_message_name"]."消息推送");
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


	

}
?>