<?php
/**
 *    #Case		bwvip
 *    #Page		FenzuAction.class.php (赛事分组)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-05-29
 */
class fenzuAction extends field_publicAction
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function fenzu()
	{
		$fenzhan_id=get("fenzhan_id");
		if($fenzhan_id)
		{
			$fenzhan_info=M()->query("select fenzhan_lun from tbl_fenzhan where fenzhan_id='".$fenzhan_id."'");
			$fenzhan_lun=$fenzhan_info[0]['fenzhan_lun'];
		}
		for($i=0; $i<$fenzhan_lun; $i++)
		{
			$lun_arr[]=$i+1;
		}
		$this->assign('lun_arr',$lun_arr);
		
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
		$this->assign('fenzhan',$fenzhan['item']);
		
		$list=D("fenzu")->fenzu_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		
		$this->assign('fenzu_on',1);
		$this->assign("page_title","赛事分组");
    	$this->display();
	}

	public function fenzu_add()
	{
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
		$this->assign('fenzhan',$fenzhan['item']);
		
		$this->assign("page_title","添加赛事分组");
    	$this->display();
	}

	public function fenzu_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["fenzu_number"]=post("fenzu_number");
			$data["fenzu_name"]=post("fenzu_name");
			$data["fenzu_tee"]=post("fenzu_tee");
			$data["fenzu_start_time"]=strtotime(post("fenzu_start_time"));
			$data["fenzu_ampm"]=post("fenzu_ampm");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["lun"]=post("lun");
			$data["fenzu_addtime"]=time();
			
			$list=M("fenzu")->add($data);
			$this->success("添加成功",U('field/fenzu/fenzu',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}
		else
		{
			$this->error("不能重复提交",U('field/fenzu/fenzu_add',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}

	}


	public function fenzu_edit()
	{
		if(intval(get("fenzu_id"))>0)
		{
			$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
			$this->assign('fenzhan',$fenzhan['item']);
		
			$data=M("fenzu")->where("fenzu_id=".intval(get("fenzu_id")))->find();
			$this->assign("data",$data);
			
			$this->assign("page_title","修改赛事分组");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function fenzu_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["fenzu_id"]=post("fenzu_id");
			$data["fenzu_number"]=post("fenzu_number");
			$data["fenzu_name"]=post("fenzu_name");
			$data["fenzu_tee"]=post("fenzu_tee");
			$data["fenzu_start_time"]=strtotime(post("fenzu_start_time"));
			$data["fenzu_ampm"]=post("fenzu_ampm");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["lun"]=post("lun");
			
			$list=M("fenzu")->save($data);
			$this->success("修改成功",U('field/fenzu/fenzu',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}
		else
		{
			$this->error("不能重复提交",U('field/fenzu/fenzu',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}

	}

	public function fenzu_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("fenzu")->where("fenzu_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function fenzu_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_fenzu set fenzu_state=1 where fenzu_id=".$ids_arr[$i]." ");
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

	public function fenzu_detail()
	{
		if(intval(get("fenzu_id"))>0)
		{
			$data=M("fenzu")->where("fenzu_id=".intval(get("fenzu_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["fenzu_name"]."赛事分组");
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