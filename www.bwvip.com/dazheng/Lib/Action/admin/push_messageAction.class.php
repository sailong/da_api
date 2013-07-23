<?php
/**
 *    #Case		bwvip
 *    #Page		Push_messageAction.class.php (消息推送)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class push_messageAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function push_message()
	{
		/*
		echo urlencode("Golf-Sense 高尔夫辅助训练系统产品介绍");
		echo "<hr>";
		echo urlencode("大正网2013全新推出Golf-Sense升级版，正火热促销中");
		*/
		
		$list=D("push_message")->push_message_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","消息推送");
    	$this->display();
	}

	public function push_message_add()
	{

		$this->assign("page_title","添加消息推送");
    	$this->display();
	}

	public function push_message_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$field_uid=post("field_uid");
			
			$max=M()->query("select max(message_number) as max_id from tbl_push_message where message_type='".post("message_type")."'  ");
			$data["message_number"]=$max[0]['max_id']+1;
			$data["message_type"]=post("message_type");
			$data["field_uid"]=post("field_uid");
			if(post("uid"))
			{
				$uid=post("uid");
				$is_push=M()->query("select if_push from pre_common_member_profile where uid='".$uid."' ");
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
			
			
			if(post("receiver_type")==3)
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
			$data['message_pic']=post("message_pic");
		
			$data["message_state"]=0;
			$data["message_totalnum"]=0;
			$data["message_sendnum"]=0;
			$data["message_errorcode"]="";
			$data["message_errormsg"]="";
			$data["message_addtime"]=time();
			$list=M("push_message")->add($data);

			if($list!=false)
			{
				if(post("message_type")=="ios")
				{
					if(post("receiver_type")==3)
					{
						$sql=" and uid='".$uid."' ";
					}
					if(post("field_uid"))
					{
						$sql .=" and field_uid='".$field_uid."' ";
					}
					else
					{
						$sql .=" and field_uid='0' ";
					}
					
					
					$aaa=M()->query("delete from tbl_push_message_list where message_id='".$list."' ".$sql." ");

					$row=M()->query("select uid,devices_token from tbl_push_devices where 1=1  $sql ");
					for($i=0; $i<count($row); $i++)
					{
						if($row[$i]['devices_token'])
						{
							$res=M()->query("insert into tbl_push_message_list (message_id,uid,field_uid,message_type,message_content,devices_token,message_state,message_addtime) values ('".$list."','".$row[$i]['uid']."','".$field_uid."','".post("message_type")."','".$msg_content."','".$row[$i]['devices_token']."',0,'".time()."') ");
						}
					}
					
				}
				$this->success("添加成功",U('admin/push_message/push_message'));
			}
			else
			{				
				$this->error("添加失败",U('admin/push_message/push_message'));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}





	public function push_message_edit()
	{
		if(intval(get("message_id"))>0)
		{
			$data=M("push_message")->where("message_id=".intval(get("message_id")))->find();
			$this->assign("data",$data);
			
			$this->assign("page_title","修改消息推送");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function push_message_edit_android()
	{
		if(intval(get("message_id"))>0)
		{
	
			$data=M("push_message")->where("message_id=".intval(get("message_id")))->find();
			//print_r($data);
			$content=json_decode($data['message_content'],true);
			//print_r($content);
			//echo $content->n_content;
			
			$data['n_content']=$content['n_content'];
			$data['ext_action']=$content['n_extras']['action'];
			$data['ext_id']=$content['n_extras']['id'];
			$data['ext_title']=urldecode($content['n_extras']['title']);

			$this->assign("data",$data);
			
			$this->assign("page_title","修改消息推送");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function push_message_edit_action()
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
			
			$list=M("push_message")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/push_message/push_message'));
			}
			else
			{
				$this->error("修改失败",U('admin/push_message/push_message'));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function push_message_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("push_message")->where("message_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function push_message_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_push_message set push_message_state=1 where message_id=".$ids_arr[$i]." ");
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

	public function push_message_detail()
	{
		if(intval(get("message_id"))>0)
		{
			$data=M("push_message")->where("message_id=".intval(get("message_id")))->find();
			if(!empty($data))
			{
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