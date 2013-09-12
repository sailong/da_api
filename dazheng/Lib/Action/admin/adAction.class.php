<?php
/**
 *    #Case		tankuang
 *    #Page		AdAction.class.php (广告)
 *
 *    @author		Zhang Long
 *    @E-mail		68779953@qq.com
 */
class adAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function ad()
	{
		
		$list=D("ad")->ad_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","广告");
    	$this->display();
	}

	public function ad_add()
	{
		$page_list=select_dict(3,"select");
		$this->assign("page_list",$page_list);
		$action_list=select_dict(16,"select");
		$this->assign("action_list",$action_list);
		$event=D('event')->event_select_pro(" and field_uid='".$_SESSION['field_uid']."' ");
		$this->assign('event',$event['item']);

		$this->assign("page_title","添加广告");
    	$this->display();
	}

	public function ad_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["ad_name"]=post("ad_name");
			
			$data["ad_app"]=post("ad_app");
			if($_FILES["ad_file"]["error"]==0 || $_FILES["ad_file_iphone4"]["error"]==0 || $_FILES["ad_file_iphone5"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/ad/");
				if($_FILES["ad_file"]["error"]==0 && $_FILES["ad_file"]["name"])
				{
					$data["ad_file"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
				}

				//ad_file_iphone4
				if($_FILES["ad_file_iphone4"]["error"]==0 && $_FILES["ad_file_iphone4"]["name"])
				{
					if($_FILES["ad_file"]["error"]==0 && $_FILES["ad_file"]["name"])
					{
						$data["ad_file_iphone4"]=$uploadinfo[1]["savepath"] . $uploadinfo[1]["savename"];
					}
					else
					{
						$data["ad_file_iphone4"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
					}
				}

				//ad_file_iphone5
				if($_FILES["ad_file_iphone5"]["error"]==0 && $_FILES["ad_file_iphone5"]["name"])
				{
					if($_FILES["ad_file"]["error"]==0 && $_FILES["ad_file"]["name"] && $_FILES["ad_file_iphone4"]["error"]==0 && $_FILES["ad_file_iphone4"]["name"])
					{
						$data["ad_file_iphone5"]=$uploadinfo[2]["savepath"] . $uploadinfo[2]["savename"];
					}
					else if($_FILES["ad_file"]["error"]==0 && $_FILES["ad_file"]["name"] && $_FILES["ad_file_iphone4"]["error"]>0)
					{
						$data["ad_file_iphone5"]=$uploadinfo[1]["savepath"] . $uploadinfo[1]["savename"];
					}
					else
					{
						$data["ad_file_iphone5"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
					}
				}
			
			}
			$data["ad_type"]='pic';
			$data["ad_width"]=post("ad_width");
			$data["ad_height"]=post("ad_height");
			$data["ad_page"]=post("ad_page");
			$data["ad_url"]=post("ad_url");
			$data["ad_sort"]=post("ad_sort");
			$data["ad_action"]=post("ad_action");
			$data["ad_action_id"]=post("ad_action_id");
			$data["ad_action_text"]=post("ad_action_text");
			$data["event_id"]=post("event_id");
			
			if(post("field_uid"))
			{
				$data["field_uid"]=post("field_uid");
			}
			
			$data["ad_state"]=1;
			$data["ad_addtime"]=time();
			$data["ad_apptype"]=post("ad_apptype");
			
			$list=M("ad")->add($data);
			if($list!=false)
			{
				msg_dialog_tip("succeed^添加成功");
			}
			else
			{
				msg_dialog_tip("error^添加失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function ad_edit()
	{
		if(intval(get("ad_id"))>0)
		{
			$data=M("ad")->where("ad_id=".intval(get("ad_id")))->find();
			$this->assign("data",$data);
			
			$event=D('event')->event_select_pro(" and field_uid='".$_SESSION['field_uid']."' ");
			$this->assign('event',$event['item']);

			$page_list=select_dict(3,"select");
			$this->assign("page_list",$page_list);
			
			$action_list=select_dict(16,"select");
			$this->assign("action_list",$action_list);
			//print_r($page_list);
			
			$this->assign("page_title","修改广告");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function ad_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["ad_id"]=post("ad_id");
			$data["ad_name"]=post("ad_name");
			$data["ad_app"]=post("ad_app");
			if($_FILES["ad_file"]["error"]==0 || $_FILES["ad_file_iphone4"]["error"]==0 || $_FILES["ad_file_iphone5"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/ad/");
				if($_FILES["ad_file"]["error"]==0 && $_FILES["ad_file"]["name"])
				{
					$data["ad_file"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
				}

				//ad_file_iphone4
				if($_FILES["ad_file_iphone4"]["error"]==0 && $_FILES["ad_file_iphone4"]["name"])
				{
					if($_FILES["ad_file"]["error"]==0 && $_FILES["ad_file"]["name"])
					{
						$data["ad_file_iphone4"]=$uploadinfo[1]["savepath"] . $uploadinfo[1]["savename"];
					}
					else
					{
						$data["ad_file_iphone4"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
					}
				}

				//ad_file_iphone5
				if($_FILES["ad_file_iphone5"]["error"]==0 && $_FILES["ad_file_iphone5"]["name"])
				{
					if($_FILES["ad_file"]["error"]==0 && $_FILES["ad_file"]["name"] && $_FILES["ad_file_iphone4"]["error"]==0 && $_FILES["ad_file_iphone4"]["name"])
					{
						$data["ad_file_iphone5"]=$uploadinfo[2]["savepath"] . $uploadinfo[2]["savename"];
					}
					else if($_FILES["ad_file"]["error"]==0 && $_FILES["ad_file"]["name"] && $_FILES["ad_file_iphone4"]["error"]>0)
					{
						$data["ad_file_iphone5"]=$uploadinfo[1]["savepath"] . $uploadinfo[1]["savename"];
					}
					else
					{
						$data["ad_file_iphone5"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
					}
				}
			
			}
			$data["ad_width"]=post("ad_width");
			$data["ad_height"]=post("ad_height");
			$data["ad_page"]=post("ad_page");
			$data["ad_url"]=post("ad_url");
			$data["ad_sort"]=post("ad_sort");
			$data["ad_action"]=post("ad_action");
			$data["ad_action_id"]=post("ad_action_id");
			$data["ad_action_text"]=post("ad_action_text");
			$data["event_id"]=post("event_id");
			if(post("field_uid"))
			{
				$data["field_uid"]=post("field_uid");
			}
			$data["ad_apptype"]=post("ad_apptype");
			
			$list=M("ad")->save($data);
			if($list!=false)
			{
				msg_dialog_tip("succeed^修改成功");
			}
			else
			{
				msg_dialog_tip("error^修改失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function ad_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("ad")->where("ad_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function ad_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_ad set ad_state=1 where ad_id=".$ids_arr[$i]." ");
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

	public function ad_detail()
	{
		if(intval(get("ad_id"))>0)
		{
			$data=M("ad")->where("ad_id=".intval(get("ad_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["ad_name"]."广告");
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