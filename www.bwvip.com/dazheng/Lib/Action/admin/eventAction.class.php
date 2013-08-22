<?php
/**
 *    #Case		bwvip
 *    #Page		EventAction.class.php (赛事)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class eventAction extends AdminAuthAction
{
	public function _initialize()
	{
		parent::_initialize();
	}

	public function event()
	{
		$list=D("event")->event_list_pro("  ");

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","赛事");
    	$this->display();
	}

	public function event_add()
	{

		import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$data['event_content'],"event_content");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);

		$this->assign("page_title","添加赛事");
    	$this->display();
	}

	public function event_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["event_uid"]=post("event_uid");
			$data["field_uid"]=0;
			$data["event_name"]=post("event_name");
		
			if($_FILES["event_logo"]["error"]==0 || $_FILES["event_timepic"]["error"]==0 || $_FILES["event_zhutui_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/event/");
				if($_FILES["event_logo"]["error"]==0 && $_FILES["event_logo"]["name"])
				{
					$data["event_logo"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
				}

				//event_timepic
				if($_FILES["event_timepic"]["error"]==0 && $_FILES["event_timepic"]["name"])
				{
					if($_FILES["event_logo"]["error"]==0 && $_FILES["event_logo"]["name"])
					{
						$data["event_timepic"]=$uploadinfo[1]["savepath"] . $uploadinfo[1]["savename"];
					}
					else
					{
						$data["event_timepic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
					}
				}

				//event_zhutui_pic
				if($_FILES["event_zhutui_pic"]["error"]==0 && $_FILES["event_zhutui_pic"]["name"])
				{
					if($_FILES["event_logo"]["error"]==0 && $_FILES["event_logo"]["name"] && $_FILES["event_timepic"]["error"]==0 && $_FILES["event_timepic"]["name"])
					{
						$data["event_zhutui_pic"]=$uploadinfo[2]["savepath"] . $uploadinfo[2]["savename"];
					}
					else if($_FILES["event_logo"]["error"]==0 && $_FILES["event_logo"]["name"] && $_FILES["event_timepic"]["error"]>0)
					{
						$data["event_zhutui_pic"]=$uploadinfo[1]["savepath"] . $uploadinfo[1]["savename"];
					}
					else
					{
						$data["event_zhutui_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
					}
				}
			
			}

			$data["event_starttime"]=strtotime(post("event_starttime"));
			$data["event_endtime"]=strtotime(post("event_endtime"));
			$data["event_baoming_starttime"]=strtotime(post("event_baoming_starttime"));
			$data["event_baoming_endtime"]=strtotime(post("event_baoming_endtime"));
			$data["event_content"]=stripslashes($_POST["event_content"]);
			$data["event_state"]=0;
			$data["event_is_tj"]=post("event_is_tj");
			$data["event_sort"]=post("event_sort");
			$data["event_fenzhan_id"]=post("event_fenzhan_id");
			$data["event_is_zhutui"]=post("event_is_zhutui");
			$data["event_is_baoming"]=post("event_is_baoming");
			$data["event_url"]=post("event_url");
			$data["event_go_action"]=post("event_go_action");
			$data["event_go_value"]=post("event_go_value");
			$data["event_lun_num"]=post("event_lun_num");
			$data["event_is_viewscore"]=post("event_is_viewscore");
			$data["event_addtime"]=time();
			$list=M("event")->add($data);
			$this->success("添加成功",U('admin/event/event'));
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function event_edit()
	{
		if(intval(get("event_id"))>0)
		{
			$data=M("event")->where("event_id=".intval(get("event_id")))->find();
			$this->assign("data",$data);

			import("@.ORG.editor");  //导入类
			$editor=new editor("400px","700px",$data['event_content'],"event_content");     //创建一个对象
			$a=$editor->createEditor();   //返回编辑器
			$b=$editor->usejs();             //js代码
			$this->assign('usejs',$b);     //输出到html
			$this->assign('editor',$a);
			
			$this->assign("page_title","修改赛事");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}
	
	

	public function event_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["event_id"]=post("event_id");
			$data["event_uid"]=post("event_uid");
			$data["event_name"]=post("event_name");

			if($_FILES["event_logo"]["error"]==0 || $_FILES["event_timepic"]["error"]==0 || $_FILES["event_zhutui_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/event/");
				if($_FILES["event_logo"]["error"]==0 && $_FILES["event_logo"]["name"])
				{
					$data["event_logo"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
				}

				//event_timepic
				if($_FILES["event_timepic"]["error"]==0 && $_FILES["event_timepic"]["name"])
				{
					if($_FILES["event_logo"]["error"]==0 && $_FILES["event_logo"]["name"])
					{
						$data["event_timepic"]=$uploadinfo[1]["savepath"] . $uploadinfo[1]["savename"];
					}
					else
					{
						$data["event_timepic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
					}
				}

				//event_zhutui_pic
				if($_FILES["event_zhutui_pic"]["error"]==0 && $_FILES["event_zhutui_pic"]["name"])
				{
					if($_FILES["event_logo"]["error"]==0 && $_FILES["event_logo"]["name"] && $_FILES["event_timepic"]["error"]==0 && $_FILES["event_timepic"]["name"])
					{
						$data["event_zhutui_pic"]=$uploadinfo[2]["savepath"] . $uploadinfo[2]["savename"];
					}
					else if($_FILES["event_logo"]["error"]==0 && $_FILES["event_logo"]["name"] && $_FILES["event_timepic"]["error"]>0)
					{
						$data["event_zhutui_pic"]=$uploadinfo[1]["savepath"] . $uploadinfo[1]["savename"];
					}
					else
					{
						$data["event_zhutui_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
					}
				}
			
			}

			

			
			$data["event_starttime"]=strtotime(post("event_starttime"));
			$data["event_endtime"]=strtotime(post("event_endtime"));
			$data["event_baoming_starttime"]=strtotime(post("event_baoming_starttime"));
			$data["event_baoming_endtime"]=strtotime(post("event_baoming_endtime"));
			$data["event_content"]=stripslashes($_POST["event_content"]);
			$data["event_sort"]=post("event_sort");
			$data["event_fenzhan_id"]=post("event_fenzhan_id");
			if(post("event_is_tj"))
			{
				$data["event_is_tj"]=post("event_is_tj");
			}
			if(post("event_is_zhutui"))
			{
				$data["event_is_zhutui"]=post("event_is_zhutui");
			}
			if(post("event_is_baoming"))
			{
				$data["event_is_baoming"]=post("event_is_baoming");
			}
			$data["event_url"]=post("event_url");
			$data["event_go_action"]=post("event_go_action");
			$data["event_go_value"]=post("event_go_value");
			$data["event_lun_num"]=post("event_lun_num");
			$data["event_is_viewscore"]=post("event_is_viewscore");
			
			$list=M("event")->save($data);
			$this->success("修改成功",U('admin/event/event_manage',array('event_id'=>$data['event_id'])));
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function event_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("event")->where("event_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function event_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_event set event_state=1 where event_id=".$ids_arr[$i]." ");
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

	public function event_detail()
	{
		if(intval(get("event_id"))>0)
		{
			$data=M("event")->where("event_id=".intval(get("event_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["event_name"]."赛事");
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
	
	
	public function event_manage()
	{
		if(intval(get("event_id"))>0)
		{
			
			
			$data=M("event")->where("event_id=".intval(get("event_id")))->find();
			$this->assign("data",$data);
			
			
			$this->assign('event_name',$data['event_name']);
			$this->assign('event_id',$data['event_id']);

			import("@.ORG.editor");  //导入类
			$editor=new editor("400px","700px",$data['event_content'],"event_content");     //创建一个对象
			$a=$editor->createEditor();   //返回编辑器
			$b=$editor->usejs();             //js代码
			$this->assign('usejs',$b);     //输出到html
			$this->assign('editor',$a);
			
			$this->assign('event_on',1);
			
			$this->assign("page_title","修改赛事");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}


	

}
?>