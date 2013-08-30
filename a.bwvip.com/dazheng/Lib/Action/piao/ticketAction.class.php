<?php
/**
 *    #Case		bwvip
 *    #Page		TicketAction.class.php (门票)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class ticketAction extends piao_publicAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function ticket()
	{
		
		
		$list=D("ticket")->ticket_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","门票");
    	$this->display();
	}

	public function ticket_add()
	{
		$event=M()->query("select event_id,event_name from tbl_event ");
		$this->assign('event',$event);
		
		import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$data["ticket_content"],"ticket_content");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);
		$this->assign("page_title","添加门票");
    	$this->display();
	}

	public function ticket_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["ticket_name"]=post("ticket_name");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["ticket_price"]=post("ticket_price");
			$data["ticket_ren_num"]=post("ticket_ren_num");
			$data["ticket_num"]=post("ticket_num");
			if($_FILES["ticket_pic"]["error"]==0)
			{
				$uploadinfo=upload_img("upload/ticket/",false,'300','300');
				$data["ticket_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["ticket_starttime"]=strtotime(post("ticket_starttime"));
			$data["ticket_endtime"]=strtotime(post("ticket_endtime"));
			$data["ticket_type"]=post("ticket_type");
			$data["ticket_times"]=post("ticket_times");
			$data["ticket_content"]=stripslashes($_POST["ticket_content"]);;
			$data["ticket_addtime"]=time();
			
			$list=M("ticket")->add($data);
			$this->success("添加成功",U('piao/ticket/ticket'));
		}
		else
		{
			$this->error("不能重复提交",U('piao/ticket/ticket_add'));
		}

	}


	public function ticket_edit()
	{
		if(intval(get("ticket_id"))>0)
		{
			
			$data=M("ticket")->where("ticket_id=".intval(get("ticket_id")))->find();
			$this->assign("data",$data);
			
			$event=M()->query("select event_id,event_name from tbl_event ");
			$this->assign('event',$event);
			
			import("@.ORG.editor");  //导入类
			$editor=new editor("400px","700px",$data["ticket_content"],"ticket_content");     //创建一个对象
			$a=$editor->createEditor();   //返回编辑器
			$b=$editor->usejs();             //js代码
			$this->assign('usejs',$b);     //输出到html
			$this->assign('editor',$a);
			
			$this->assign("page_title","修改门票");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function ticket_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["ticket_id"]=post("ticket_id");
			$data["ticket_name"]=post("ticket_name");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["ticket_price"]=post("ticket_price");
			$data["ticket_ren_num"]=post("ticket_ren_num");
			$data["ticket_num"]=post("ticket_num");
			if($_FILES["ticket_pic"]["error"]==0)
			{
				$uploadinfo=upload_img("upload/ticket/",false,'300','300');
				$data["ticket_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["ticket_starttime"]=strtotime(post("ticket_starttime"));
			$data["ticket_endtime"]=strtotime(post("ticket_endtime"));
			$data["ticket_type"]=post("ticket_type");
			$data["ticket_times"]=post("ticket_times");
			$data["ticket_content"]=stripslashes($_POST["ticket_content"]);;
			
			$list=M("ticket")->save($data);
			$this->success("修改成功",U('piao/ticket/ticket'));			
		}
		else
		{
			$this->error("不能重复提交",U('piao/ticket/ticket'));
		}

	}

	public function ticket_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("ticket")->where("ticket_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function ticket_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_ticket set ticket_state=1 where ticket_id=".$ids_arr[$i]." ");
			}
			if($res)
			{
				echo "succeed^审核成功";
			}
			else
			{
				echo "error^审核失败";
			}			
			
		}
	}

	public function ticket_detail()
	{
		if(intval(get("ticket_id"))>0)
		{
			$data=M("ticket")->where("ticket_id=".intval(get("ticket_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["ticket_name"]."门票");
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