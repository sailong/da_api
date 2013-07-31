<?php
/**
 *    #Case		bwvip
 *    #Page		EventAction.class.php (赛事)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class eventAction extends field_publicAction
{
	public function _initialize()
	{
		parent::_initialize();
	}

	public function event()
	{
		$list=D("event")->event_list_pro(" and field_uid='".$_SESSION['field_uid']."' ");

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
			$data["field_uid"]=$_SESSION['field_uid'];
			$data["event_name"]=post("event_name");
			$data["event_type"]=post("event_type");
			$data["event_left"]=post("event_left");
			$data["event_left_intro"]=post("event_left_intro");
			//$data["event_left_pic"]=post("event_left_pic");
			$data["event_right"]=post("event_right");
			$data["event_right_intro"]=post("event_right_intro");
			//$data["event_right_pic"]=post("event_right_pic");
			
			if($_FILES["event_logo"]["error"]==0 || $_FILES["event_timepic"]["error"]==0 || $_FILES["event_zhutui_pic"]["error"]==0 || $_FILES["event_left_pic"]["error"]==0 || $_FILES["event_right_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/event/");
				foreach($uploadinfo as $key=>$val){
					$uploadinfo[$val['key']] = $val;
					unset($uploadinfo[$key]);
				}
				if(!empty($uploadinfo["event_logo"]))
				{
					$data["event_logo"]=$uploadinfo["event_logo"]["savepath"] . $uploadinfo["event_logo"]["savename"];
				}

				//event_timepic
				if(!empty($uploadinfo["event_timepic"]))
				{
					$data["event_timepic"]=$uploadinfo["event_timepic"]["savepath"] . $uploadinfo["event_timepic"]["savename"];
				}

				//event_zhutui_pic
				if(!empty($uploadinfo["event_zhutui_pic"]))
				{
					$data["event_zhutui_pic"]=$uploadinfo["event_zhutui_pic"]["savepath"] . $uploadinfo["event_zhutui_pic"]["savename"];
				}
				//event_left_pic
				if(!empty($uploadinfo["event_left_pic"])) {
					$data["event_left_pic"]=$uploadinfo["event_left_pic"]["savepath"] . $uploadinfo["event_left_pic"]["savename"];
				}
				//event_right_pic
				if(!empty($uploadinfo["event_right_pic"])) {
					$data["event_right_pic"]=$uploadinfo["event_right_pic"]["savepath"] . $uploadinfo["event_right_pic"]["savename"];
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
			$data["event_addtime"]=time();
			$list=M("event")->add($data);
			$this->success("添加成功",U('field/event/event'));
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
			
			$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
			//echo '<pre>';
			
			//var_dump($data);
			$this->assign('fenzhan',$fenzhan['item']);
			
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
			$data["event_left"]=post("event_left");
			$data["event_left_intro"]=post("event_left_intro");
			//$data["event_left_pic"]=post("event_left_pic");
			$data["event_right"]=post("event_right");
			$data["event_right_intro"]=post("event_right_intro");
			//$data["event_right_pic"]=post("event_right_pic");
			
			if($_FILES["event_logo"]["error"]==0 || $_FILES["event_timepic"]["error"]==0 || $_FILES["event_zhutui_pic"]["error"]==0 || $_FILES["event_left_pic"]["error"]==0 || $_FILES["event_right_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/event/");
				foreach($uploadinfo as $key=>$val){
					$uploadinfo[$val['key']] = $val;
					unset($uploadinfo[$key]);
				}
				if(!empty($uploadinfo["event_logo"]))
				{
					$data["event_logo"]=$uploadinfo["event_logo"]["savepath"] . $uploadinfo["event_logo"]["savename"];
				}

				//event_timepic
				if(!empty($uploadinfo["event_timepic"]))
				{
					$data["event_timepic"]=$uploadinfo["event_timepic"]["savepath"] . $uploadinfo["event_timepic"]["savename"];
				}

				//event_zhutui_pic
				if(!empty($uploadinfo["event_zhutui_pic"]))
				{
					$data["event_zhutui_pic"]=$uploadinfo["event_zhutui_pic"]["savepath"] . $uploadinfo["event_zhutui_pic"]["savename"];
				}
				//event_left_pic
				if(!empty($uploadinfo["event_left_pic"])) {
					$data["event_left_pic"]=$uploadinfo["event_left_pic"]["savepath"] . $uploadinfo["event_left_pic"]["savename"];
				}
				//event_right_pic
				if(!empty($uploadinfo["event_right_pic"])) {
					$data["event_right_pic"]=$uploadinfo["event_right_pic"]["savepath"] . $uploadinfo["event_right_pic"]["savename"];
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
			
			
			$list=M("event")->save($data);
			$this->success("修改成功",U('field/event/event_manage',array('event_id'=>$data['event_id'])));
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
			
			$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
			$this->assign('fenzhan',$fenzhan['item']);
			
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