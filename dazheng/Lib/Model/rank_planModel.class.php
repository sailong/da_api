<?php
/**
 *    #Case		bwvip
 *    #Page		Rank_planAction.class.php (积分方案)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2014-03-05
 */
class rank_planModel extends Model{

	//list and page
	function rank_plan_list_pro($bigwhere="", $page_size=20, $sort=" rank_plan_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and rank_plan_name like '%".get("k")."%' ";
		}

		if(get("starttime")!="")
		{
			$where .=" and rank_plan_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and rank_plan_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("rank_plan")->where($where.$bigwhere)->field("rank_plan_id,rank_plan_type,event_level,rank_1,rank_2,rank_3,rank_4,rank_5,rank_6,rank_7,rank_8,rank_9,rank_10,rank_11,rank_12,rank_13,rank_14,rank_15,rank_16,rank_17,rank_18,rank_19,rank_20,rank_21,rank_22,rank_23,rank_24,rank_25,rank_26,rank_27,rank_28,rank_29,rank_30,rank_31,rank_32,rank_33,rank_34,rank_35,rank_36,rank_37,rank_38,rank_39,rank_40,rank_41,rank_42,rank_43,rank_44,rank_45,rank_46,rank_47,rank_48,rank_49,rank_50,rank_51,rank_52,rank_53,rank_54,rank_55,rank_56,rank_57,rank_58,rank_59,rank_60,rank_61,rank_62,rank_63,rank_64,rank_65,rank_66,rank_67,rank_68,rank_69,rank_70,rank_plan_addtime")->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("rank_plan")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function rank_plan_select_pro($bigwhere="",$limit=999999, $sort=" rank_plan_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("rank_plan")->where($where.$bigwhere)->field("rank_plan_id,rank_plan_type,event_level,rank_1,rank_2,rank_3,rank_4,rank_5,rank_6,rank_7,rank_8,rank_9,rank_10,rank_11,rank_12,rank_13,rank_14,rank_15,rank_16,rank_17,rank_18,rank_19,rank_20,rank_21,rank_22,rank_23,rank_24,rank_25,rank_26,rank_27,rank_28,rank_29,rank_30,rank_31,rank_32,rank_33,rank_34,rank_35,rank_36,rank_37,rank_38,rank_39,rank_40,rank_41,rank_42,rank_43,rank_44,rank_45,rank_46,rank_47,rank_48,rank_49,rank_50,rank_51,rank_52,rank_53,rank_54,rank_55,rank_56,rank_57,rank_58,rank_59,rank_60,rank_61,rank_62,rank_63,rank_64,rank_65,rank_66,rank_67,rank_68,rank_69,rank_70,rank_plan_addtime")->order($sort)->limit($limit)->select();
		$data["total"]=M("rank_plan")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>