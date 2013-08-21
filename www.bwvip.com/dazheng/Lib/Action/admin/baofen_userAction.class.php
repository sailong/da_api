<?php
/**
 *    #Case		bwvip
 *    #Page		Baofen_userAction.class.php (报分员)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-05-29
 */
class baofen_userAction extends AdminAuthAction
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function baofen_user()
	{
		$this->assign('baofen_user_on',1);
		
		$event_info=M("event")->where("event_id=".intval(get("event_id"))." and fenzhan_id=".intval(get("fenzhan_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
		$this->assign('fenzhan',$fenzhan['item']);
		
		$list=D("baofen_user")->baofen_user_list_pro();
		
		
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","报分员");
    	$this->display();
	}

	public function baofen_user_add()
	{
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
		$this->assign('fenzhan',$fenzhan['item']);
		//print_r($fenzhan);
		
		
		$this->assign("page_title","添加报分员");
    	$this->display();
	}

	public function baofen_user_add_action()
	{
		
		if(M()->autoCheckToken($_POST))
		{
			$fenzhan_info=M()->query("select field_id from tbl_fenzhan where fenzhan_id='".post("fenzhan_id")."' ");
			if($fenzhan_info[0]["field_id"])
			{
				$data["field_id"]=$fenzhan_info[0]["field_id"];
			}
			$data["username"]=post("username");
			$data["password"]=post("password");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			
			$data["dongs"]=implode(",",$_POST["dongs"]);
			$data["iteamid"]=post("iteamid");
			$data["onlymark"]=post("onlymark");
			$data["addtime"]=time();
			
			$list=M("baofen_user")->add($data);
			$this->success("添加成功",U('admin/baofen_user/baofen_user',array('event_id'=>$data["event_id"])));
		}
		else
		{
			$this->error("不能重复提交",U('admin/baofen_user/baofen_user_add',array('event_id'=>$data["event_id"])));
		}
	}


	
	public function baofen_user_edit()
	{
		if(intval(get("baofen_user_id"))>0)
		{
			
		
			$data=M("baofen_user")->where("baofen_user_id=".intval(get("baofen_user_id")))->find();
			$this->assign("data",$data);
			
			$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".$data["event_id"]."' ");
			$this->assign('fenzhan',$fenzhan['item']);
			
			
			
			$this->assign("page_title","修改报分员");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function baofen_user_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$fenzhan_info=M()->query("select field_id from tbl_fenzhan where fenzhan_id='".post("fenzhan_id")."' ");
			if($fenzhan_info[0]["field_id"])
			{
				$data["field_id"]=$fenzhan_info[0]["field_id"];
			}
			$data["baofen_user_id"]=post("baofen_user_id");
			$data["username"]=post("username");
			$data["password"]=post("password");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["dongs"]=implode(",",$_POST["dongs"]);
			$data["iteamid"]=post("iteamid");
			$data["onlymark"]=post("onlymark");
			
			$list=M("baofen_user")->save($data);
			$this->success("修改成功",U('admin/baofen_user/baofen_user',array('event_id'=>$data["event_id"])));
		}
		else
		{
			$this->error("不能重复提交",U('admin/baofen_user/baofen_user',array('event_id'=>$data["event_id"])));
		}

	}

	public function baofen_user_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("baofen_user")->where("baofen_user_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function baofen_user_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_baofen_user set baofen_user_state=1 where baofen_user_id=".$ids_arr[$i]." ");
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

	public function baofen_user_detail()
	{
		if(intval(get("baofen_user_id"))>0)
		{
			$data=M("baofen_user")->where("baofen_user_id=".intval(get("baofen_user_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["baofen_user_name"]."报分员");
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