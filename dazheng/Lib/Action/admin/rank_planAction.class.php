<?php
/**
 *    #Case		bwvip
 *    #Page		Rank_planAction.class.php (积分方案)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2014-03-05
 */
class rank_planAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function rank_plan()
	{
		$list=D("rank_plan")->rank_plan_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","积分方案");
    	$this->display();
	}

	public function rank_plan_add()
	{
		
		$this->assign("page_title","添加积分方案");
    	$this->display();
	}

	public function rank_plan_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["rank_plan_type"]=post("rank_plan_type");
			$data["event_level"]=post("event_level");
			$data["rank_1"]=post("rank_1");
			$data["rank_2"]=post("rank_2");
			$data["rank_3"]=post("rank_3");
			$data["rank_4"]=post("rank_4");
			$data["rank_5"]=post("rank_5");
			$data["rank_6"]=post("rank_6");
			$data["rank_7"]=post("rank_7");
			$data["rank_8"]=post("rank_8");
			$data["rank_9"]=post("rank_9");
			$data["rank_10"]=post("rank_10");
			$data["rank_11"]=post("rank_11");
			$data["rank_12"]=post("rank_12");
			$data["rank_13"]=post("rank_13");
			$data["rank_14"]=post("rank_14");
			$data["rank_15"]=post("rank_15");
			$data["rank_16"]=post("rank_16");
			$data["rank_17"]=post("rank_17");
			$data["rank_18"]=post("rank_18");
			$data["rank_19"]=post("rank_19");
			$data["rank_20"]=post("rank_20");
			$data["rank_21"]=post("rank_21");
			$data["rank_22"]=post("rank_22");
			$data["rank_23"]=post("rank_23");
			$data["rank_24"]=post("rank_24");
			$data["rank_25"]=post("rank_25");
			$data["rank_26"]=post("rank_26");
			$data["rank_27"]=post("rank_27");
			$data["rank_28"]=post("rank_28");
			$data["rank_29"]=post("rank_29");
			$data["rank_30"]=post("rank_30");
			$data["rank_31"]=post("rank_31");
			$data["rank_32"]=post("rank_32");
			$data["rank_33"]=post("rank_33");
			$data["rank_34"]=post("rank_34");
			$data["rank_35"]=post("rank_35");
			$data["rank_36"]=post("rank_36");
			$data["rank_37"]=post("rank_37");
			$data["rank_38"]=post("rank_38");
			$data["rank_39"]=post("rank_39");
			$data["rank_40"]=post("rank_40");
			$data["rank_41"]=post("rank_41");
			$data["rank_42"]=post("rank_42");
			$data["rank_43"]=post("rank_43");
			$data["rank_44"]=post("rank_44");
			$data["rank_45"]=post("rank_45");
			$data["rank_46"]=post("rank_46");
			$data["rank_47"]=post("rank_47");
			$data["rank_48"]=post("rank_48");
			$data["rank_49"]=post("rank_49");
			$data["rank_50"]=post("rank_50");
			$data["rank_51"]=post("rank_51");
			$data["rank_52"]=post("rank_52");
			$data["rank_53"]=post("rank_53");
			$data["rank_54"]=post("rank_54");
			$data["rank_55"]=post("rank_55");
			$data["rank_56"]=post("rank_56");
			$data["rank_57"]=post("rank_57");
			$data["rank_58"]=post("rank_58");
			$data["rank_59"]=post("rank_59");
			$data["rank_60"]=post("rank_60");
			$data["rank_61"]=post("rank_61");
			$data["rank_62"]=post("rank_62");
			$data["rank_63"]=post("rank_63");
			$data["rank_64"]=post("rank_64");
			$data["rank_65"]=post("rank_65");
			$data["rank_66"]=post("rank_66");
			$data["rank_67"]=post("rank_67");
			$data["rank_68"]=post("rank_68");
			$data["rank_69"]=post("rank_69");
			$data["rank_70"]=post("rank_70");
			$data["rank_plan_addtime"]=time();
			
			$list=M("rank_plan")->add($data);
			$this->success("添加成功",U('admin/rank_plan/rank_plan'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/rank_plan/rank_plan_add'));
		}

	}


	public function rank_plan_edit()
	{
		if(intval(get("rank_plan_id"))>0)
		{
			$data=M("rank_plan")->where("rank_plan_id=".intval(get("rank_plan_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改积分方案");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function rank_plan_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["rank_plan_id"]=post("rank_plan_id");
			$data["rank_plan_type"]=post("rank_plan_type");
			$data["event_level"]=post("event_level");
			$data["rank_1"]=post("rank_1");
			$data["rank_2"]=post("rank_2");
			$data["rank_3"]=post("rank_3");
			$data["rank_4"]=post("rank_4");
			$data["rank_5"]=post("rank_5");
			$data["rank_6"]=post("rank_6");
			$data["rank_7"]=post("rank_7");
			$data["rank_8"]=post("rank_8");
			$data["rank_9"]=post("rank_9");
			$data["rank_10"]=post("rank_10");
			$data["rank_11"]=post("rank_11");
			$data["rank_12"]=post("rank_12");
			$data["rank_13"]=post("rank_13");
			$data["rank_14"]=post("rank_14");
			$data["rank_15"]=post("rank_15");
			$data["rank_16"]=post("rank_16");
			$data["rank_17"]=post("rank_17");
			$data["rank_18"]=post("rank_18");
			$data["rank_19"]=post("rank_19");
			$data["rank_20"]=post("rank_20");
			$data["rank_21"]=post("rank_21");
			$data["rank_22"]=post("rank_22");
			$data["rank_23"]=post("rank_23");
			$data["rank_24"]=post("rank_24");
			$data["rank_25"]=post("rank_25");
			$data["rank_26"]=post("rank_26");
			$data["rank_27"]=post("rank_27");
			$data["rank_28"]=post("rank_28");
			$data["rank_29"]=post("rank_29");
			$data["rank_30"]=post("rank_30");
			$data["rank_31"]=post("rank_31");
			$data["rank_32"]=post("rank_32");
			$data["rank_33"]=post("rank_33");
			$data["rank_34"]=post("rank_34");
			$data["rank_35"]=post("rank_35");
			$data["rank_36"]=post("rank_36");
			$data["rank_37"]=post("rank_37");
			$data["rank_38"]=post("rank_38");
			$data["rank_39"]=post("rank_39");
			$data["rank_40"]=post("rank_40");
			$data["rank_41"]=post("rank_41");
			$data["rank_42"]=post("rank_42");
			$data["rank_43"]=post("rank_43");
			$data["rank_44"]=post("rank_44");
			$data["rank_45"]=post("rank_45");
			$data["rank_46"]=post("rank_46");
			$data["rank_47"]=post("rank_47");
			$data["rank_48"]=post("rank_48");
			$data["rank_49"]=post("rank_49");
			$data["rank_50"]=post("rank_50");
			$data["rank_51"]=post("rank_51");
			$data["rank_52"]=post("rank_52");
			$data["rank_53"]=post("rank_53");
			$data["rank_54"]=post("rank_54");
			$data["rank_55"]=post("rank_55");
			$data["rank_56"]=post("rank_56");
			$data["rank_57"]=post("rank_57");
			$data["rank_58"]=post("rank_58");
			$data["rank_59"]=post("rank_59");
			$data["rank_60"]=post("rank_60");
			$data["rank_61"]=post("rank_61");
			$data["rank_62"]=post("rank_62");
			$data["rank_63"]=post("rank_63");
			$data["rank_64"]=post("rank_64");
			$data["rank_65"]=post("rank_65");
			$data["rank_66"]=post("rank_66");
			$data["rank_67"]=post("rank_67");
			$data["rank_68"]=post("rank_68");
			$data["rank_69"]=post("rank_69");
			$data["rank_70"]=post("rank_70");
			
			$list=M("rank_plan")->save($data);
			$this->success("修改成功",U('admin/rank_plan/rank_plan'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/rank_plan/rank_plan'));
		}

	}

	public function rank_plan_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("rank_plan")->where("rank_plan_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function rank_plan_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_rank_plan set rank_plan_state=1 where rank_plan_id=".$ids_arr[$i]." ");
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

	public function rank_plan_detail()
	{
		if(intval(get("rank_plan_id"))>0)
		{
			$data=M("rank_plan")->where("rank_plan_id=".intval(get("rank_plan_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["rank_plan_name"]."积分方案");
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